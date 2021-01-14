<?php namespace MG\PageBuilder\Controllers;

use Backend\Classes\Controller;
use Illuminate\Http\JsonResponse;
use MG\PageBuilder\Traits\ConfigFileTrait;
use MG\PageBuilder\Traits\ContentDataTrait;
use Backend\Models\User;
use BackendAuth;
/**
 * Page Builder Side/Content Blocks Menu Data controller
 *
 * @package mg\pagebuilder
 * @author Matthew Guillot
 */
class BlocksData extends Controller
{
    use ConfigFileTrait;
    use ContentDataTrait;

    /**
     * Return JSON Response of all Content Block Categories and assets
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
            $defaultCat = $this->configValue($configvals['blocks_default_category_id']);
            $categoryarr = $this->getBlockCategories();
            $categories = $categoryarr['categories'];
            $catendnum = $categoryarr['catend'];

            $this->output->defaultcat = $defaultCat;

            foreach ($categories as $key => $category) {
                foreach ($category as $blocks) {
                    $catnum = $key + 1;
                    if ($catnum <= $catendnum) {
                        $this->output->categories[] = $blocks->assets;
                        $this->output->category_index[$catnum] = $blocks->name;
                    } else {
                        $this->output->more[] = $blocks->assets;
                        $this->output->more_index[$catnum] = $blocks->name;
                    }
                }
            }
            return JsonResponse::create($this->output);
        } else {
            $this->output->errors = ['Theme not found'];
            return JsonResponse::create($this->output, 422);
        }
    }
}
