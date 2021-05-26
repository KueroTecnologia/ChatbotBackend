<?php


declare(strict_types=1);

namespace KED\Module\Catalog\Middleware\Category\View;

use GraphQL\Language\Parser;
use GraphQL\Language\Source;
use GraphQL\Type\Schema;
use GraphQL\Validator\DocumentValidator;
use function KED\get_base_url;
use KED\Services\Http\Request;
use KED\Services\Http\Response;
use KED\Middleware\MiddlewareAbstract;

class QueryValidateMiddleware extends MiddlewareAbstract
{
    /**
     * @param Request $request
     * @param Response $response
     * @param null $delegate
     * @return mixed
     */
    public function __invoke(Request $request, Response $response, $delegate = null)
    {
        $queries = $request->query->all();
        unset($queries["ajax"]);
        $catId = $request->attributes->get('id');
        if (!$queries) {
            $request->attributes->set("filters", "[{key: \"category\", operator: \"=\", value: \"$catId\"}]");
            return $delegate;
        }

        $filters = ["{key: \"category\", operator: \"=\", value: \"$catId\"}"];
        foreach ($queries as $key => $val) {
            if (strpos($val, ',') !== false) {
                $filters[] = "{key: \"$key\", operator: \"IN\", value: \"$val\"}";
            } else if (strpos($val, '-') !== false) {
                $filters[] = "{key: \"$key\", operator: \"BETWEEN\", value: \"$val\"}";
            } else {
                $filters[] = "{key: \"$key\", operator: \"=\", value: \"$val\"}";
            }
        }

        $filters = "[" . implode(",", $filters) . "]";
        $query = "{productCollection (filters: $filters) {products {product_id}}}";
        try {
            $documentNode = Parser::parse(new Source($query, 'GraphQL'));
            $errors = DocumentValidator::validate($this->getContainer()->get(Schema::class), $documentNode);
            if (!empty($errors))
                throw new \Exception("Query is invalid. Need to use the root one");
            $request->attributes->set("filters", $filters);

            return $delegate;
        } catch (\Exception $e) {
            $response->redirect(get_base_url() . $request->getUri());
            return $response;
        }
    }
}