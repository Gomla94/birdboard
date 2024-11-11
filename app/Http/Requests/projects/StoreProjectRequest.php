<?php

namespace App\Http\Requests\projects;

use App\Models\Project;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StoreProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('update', $this->project());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => ['sometimes', 'string'],
            'description' => ['sometimes', 'string'],
            'notes' => ['string', 'nullable']
        ];
    }

    protected function project()
    {
        return Project::findorFail($this->route('project'));
    }

    public function persist()
    {
        $project = Project::findorFail($this->route('project'));
        $project->update($this->validated());
        return $project;
    }
}
