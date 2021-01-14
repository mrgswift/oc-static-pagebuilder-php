<?php namespace MG\PageBuilder\Controllers;

use Backend\Classes\Controller;
use Illuminate\Http\JsonResponse;
use MG\PageBuilder\Traits\ConfigFileTrait;
use MG\PageBuilder\Traits\ContentDataTrait;
use Backend\Models\User;
use BackendAuth;

/**
 * Page Builder Snippets Data controller
 *
 * @package mg\pagebuilder
 * @author Matthew Guillot
 */
class SnippetsData extends Controller
{
    use ConfigFileTrait;
    use ContentDataTrait;

    /**
     * Return JSON Response of all Snippet Categories and assets
     *
     * @return JsonResponse
     */
    public function index()
    {
        if (!BackendAuth::getUser() instanceof User) {
            return JsonResponse::create(['message' => 'Access to this resource is denied'], 401);
        }

        if (!empty($this->theme)) {
            $configvals = $this->getConfig('theme', $this->theme);
            $defaultCategory = $this->configValue($configvals['snippets_default_category_id']);
            $snippetCategories = $this->getSnippetCategories();
            $snippets = $snippetCategories['snippets'];

            foreach ($snippets as $key => $category) {
                foreach ($category as $blocks) {
                    $this->output->snippets[] = $blocks->assets;
                    $this->output->snippets_index[$key] = $blocks->name;
                    $this->output->snippets_default_category = $defaultCategory;
                }
            }
            return JsonResponse::create($this->output);
        } else {
            $this->output->errors = ['Theme not found'];
            return JsonResponse::create($this->output, 422);
        }
    }
}
