<?php

declare(strict_types=1);

namespace Bolt\Controller\Frontend;

use Bolt\Configuration\Config;
use Bolt\Content\ContentType;
use Bolt\Controller\BaseController;
use Bolt\Entity\Content;
use Bolt\Repository\ContentRepository;
use Bolt\TemplateChooser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ListingController extends BaseController
{
    public function __construct(Config $config, CsrfTokenManagerInterface $csrfTokenManager, TemplateChooser $templateChooser)
    {
        parent::__construct($config, $csrfTokenManager);

        $this->templateChooser = $templateChooser;
    }

    /**
     * @Route(
     *     "/{contenttypeslug}",
     *     name="contentlisting",
     *     requirements={"contenttypeslug"="%bolt.requirement.contenttypes%"},
     *     methods={"GET"})
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function listing(ContentRepository $content, Request $request, string $contenttypeslug): Response
    {
        $page = (int) $request->query->get('page', 1);

        /** @var Content[] $records */
        $records = $content->findForPage($page);

        $contenttype = ContentType::factory($contenttypeslug, $this->config->get('contenttypes'));

        $templates = $this->templateChooser->listing($contenttype);

        return $this->renderTemplate($templates, ['records' => $records]);
    }

    /**
     * Route alias for Bolt 3 backwards compatibility
     * Deprecated since 4.0
     *
     * @Route(
     *     "/{contenttypeslug}",
     *     name="contentlisting",
     *     requirements={"contenttypeslug"="%bolt.requirement.contenttypes%"},
     *     methods={"GET"})
     */
    public function contentListing(ContentRepository $content, Request $request, string $contenttypeslug): Response
    {
        return $this->listing($content, $request, $contenttypeslug);
    }
}
