<?php

declare(strict_types=1);

namespace Bolt\Controller\Backend;

use Bolt\Content\ContentType;
use Bolt\Controller\BaseController;
use Bolt\Repository\ContentRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ContentOverviewController.
 *
 * @Security("has_role('ROLE_ADMIN')")
 */
class ContentOverviewController extends BaseController
{
    /**
     * @Route("/content/{contenttype}", name="bolt_content_overview")
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function overview(ContentRepository $content, Request $request, string $contenttype = ''): Response
    {
        $contenttype = ContentType::factory($contenttype, $this->config->get('contenttypes'));

        $page = (int) $request->query->get('page', 1);

        $records = $content->findForPage($page, $contenttype);

        return $this->renderTemplate('content/listing.html.twig', [
            'records' => $records,
            'contenttype' => $contenttype,
        ]);
    }
}
