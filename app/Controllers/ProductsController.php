<?php

namespace App\Controllers;

use App\Redirect;
use App\Services\Products\AddServiceRequest;
use App\Services\Products\AddService;
use App\Services\Products\AddTemplateService;
use App\Services\Products\DeleteServiceRequest;
use App\Services\Products\DeleteService;
use App\Services\Products\EditServiceRequest;
use App\Services\Products\EditService;
use App\Services\Products\EditTemplateService;
use App\Services\Products\EditTemplateServiceRequest;
use App\Services\Products\FilterServiceRequest;
use App\Services\Products\FilterService;
use App\Services\Products\FilterTemplateService;
use App\Services\Products\ShowServiceRequest;
use App\Services\Products\ShowService;
use Twig\Environment;


class ProductsController
{
    private Environment $twig;
    private ShowService $showService;
    private AddService $addService;
    private EditService $editService;
    private FilterService $filterService;
    private DeleteService $deleteService;
    private AddTemplateService $addTemplateService;
    private EditTemplateService $editTemplateService;
    private FilterTemplateService $filterTemplateService;


    public function __construct(Environment           $twig,
                                ShowService           $showService,
                                AddService            $addService,
                                EditService           $editService,
                                FilterService         $filterService,
                                DeleteService         $deleteService,
                                AddTemplateService    $addTemplateService,
                                EditTemplateService   $editTemplateService,
                                FilterTemplateService $filterTemplateService)
    {

        $this->twig = $twig;
        $this->showService = $showService;
        $this->addService = $addService;
        $this->editService = $editService;
        $this->filterService = $filterService;
        $this->deleteService = $deleteService;
        $this->addTemplateService = $addTemplateService;
        $this->editTemplateService = $editTemplateService;
        $this->filterTemplateService = $filterTemplateService;
    }

    public function index(): void
    {
        echo $this->twig->render('Products/index.twig');
    }

    public function show(): void
    {
        $showServiceRequest = new ShowServiceRequest($_SESSION['id']);
        $serviceResponse = $this->showService->execute($showServiceRequest);

        echo $this->twig->render('Products/show.twig', $serviceResponse->getAll());
    }

    public function addTemplate(): void
    {
        $serviceResponse = $this->addTemplateService->execute();

        echo $this->twig->render('Products/add.twig',
            $serviceResponse->getAll());
    }

    public function add(): void
    {
        $addServiceRequest = new AddServiceRequest($_POST, $_SESSION['id']);

        $this->addService->execute($addServiceRequest);

        Redirect::url('/products/show');


    }

    public function editTemplate(array $productId): void
    {
        $editTemplateServiceRequest = new EditTemplateServiceRequest($productId['id'], $_SESSION['id']);
        $serviceResponse = $this->editTemplateService->execute($editTemplateServiceRequest);

        echo $this->twig->render('Products/edit.twig',
            $serviceResponse->getAll());
    }


    public function edit(array $productId): void
    {
        $editServiceRequest = new EditServiceRequest($_POST, $productId, $_SESSION['id']);
        $this->editService->execute($editServiceRequest);

        Redirect::url('/products/show');

    }

    public function filterTemplate(): void
    {
        $serviceResponse = $this->filterTemplateService->execute();
        echo $this->twig->render('Products/filter.twig',
            $serviceResponse->getAll());
    }

    public function filter(): void
    {
        $filterServiceRequest = new FilterServiceRequest($_GET, $_SESSION['id']);

        $serviceResponse = $this->filterService->execute($filterServiceRequest);

        echo $this->twig->render('Products/show.twig',
            $serviceResponse->getAll());

    }

    public function delete(array $id): void
    {
        $deleteServiceRequest = new DeleteServiceRequest($id['id'], $_SESSION['id']);
        $this->deleteService->execute($deleteServiceRequest);
        Redirect::url('/products/show');
    }

}