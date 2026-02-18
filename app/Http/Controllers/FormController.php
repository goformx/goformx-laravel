<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFormRequest;
use App\Http\Requests\UpdateFormRequest;
use App\Services\GoFormsClient;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FormController extends Controller
{
    public function __construct(
        private readonly GoFormsClient $goFormsClient
    ) {}

    public function index(): Response|RedirectResponse
    {
        try {
            $client = $this->goFormsClient->withUser(auth()->user());
            $forms = $client->listForms();
        } catch (RequestException $e) {
            return $this->handleGoError($e, request());
        }

        return Inertia::render('Forms/Index', [
            'forms' => $forms,
        ]);
    }

    public function show(Request $request, string $id): Response|RedirectResponse
    {
        $client = $this->goFormsClient->withUser(auth()->user());
        $form = $client->getForm($id);

        if ($form === null) {
            throw new NotFoundHttpException('Form not found.');
        }

        return Inertia::render('Forms/Edit', [
            'form' => $form,
        ]);
    }

    public function store(StoreFormRequest $request): RedirectResponse
    {
        try {
            $client = $this->goFormsClient->withUser(auth()->user());
            $form = $client->createForm($request->validated());
        } catch (RequestException $e) {
            return $this->handleGoError($e, $request);
        }

        $formId = $form['id'] ?? $form['ID'] ?? null;

        return redirect()->route('forms.show', $formId)
            ->with('success', 'Form created successfully.');
    }

    public function update(UpdateFormRequest $request, string $id): RedirectResponse
    {
        try {
            $client = $this->goFormsClient->withUser(auth()->user());
            $client->updateForm($id, $request->validated());
        } catch (RequestException $e) {
            return $this->handleGoError($e, $request);
        }

        return redirect()->back()->with('success', 'Form updated successfully.');
    }

    public function destroy(string $id): RedirectResponse
    {
        try {
            $client = $this->goFormsClient->withUser(auth()->user());
            $client->deleteForm($id);
        } catch (RequestException $e) {
            return $this->handleGoError($e, request());
        }

        return redirect()->route('forms.index')->with('success', 'Form deleted successfully.');
    }

    private function handleGoError(RequestException $e, Request $request): RedirectResponse
    {
        if ($e->response === null) {
            Log::error('GoForms API error (no response)', ['error' => $e->getMessage()]);

            return redirect()->back()->with('error', 'An unexpected error occurred.')->withInput();
        }

        $status = $e->response->status();

        if ($status === 404) {
            throw new NotFoundHttpException('Resource not found.');
        }

        if ($status === 422) {
            $errors = $e->response->json('errors', $e->response->json('message', 'Validation failed.'));
            if (is_array($errors)) {
                throw ValidationException::withMessages($errors);
            }

            throw ValidationException::withMessages(['form' => is_string($errors) ? $errors : 'Validation failed.']);
        }

        if ($status >= 500) {
            Log::error('GoForms API server error', ['status' => $status, 'body' => $e->response->body()]);

            return redirect()->back()->with('error', 'The service is temporarily unavailable. Try again later.')->withInput();
        }

        return redirect()->back()->with('error', 'An error occurred. Please try again.')->withInput();
    }
}
