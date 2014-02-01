<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Controller;

use Phalcon\Mvc\Controller;
use Phalcon\Assets\Manager as AssetsManager;
use Lolphp\EntityManagerInterface;
use Lolphp\Model\Region as Region;

class ControllerBase extends Controller
{
    /**
     * @var AssetsManager
     */
    protected $assets;

    /**
     * @var EntityManagerInterface $em
     */
    protected $em;

    public function initialize()
    {
        /**
         * @var AssetsManager $assets
         */
        $this->assets       = $this->getDI()->get('assets');
        $this->em           = $this->getDI()->get('em');

        $assets             = &$this->assets;
        $assets->addCss('css/bootstrap.min.css');
        $assets->addCss('css/jquery-ui-1.9.2.custom.css');
        $assets->addCss('css/bootstrap-theme.min.css');
        $assets->addJs('js/jquery-2.1.0.min.js');
        $assets->addJs('js/jquery-ui-1.9.2.custom.min.js');
        $assets->addJs('js/bootstrap.min.js');
        $assets->addJs('js/jquery.clipboard.js');
        $assets->addJs('js/lolphp.js');

        $this->setViewTitle('League of Legends API', 'League of Legends API');

        $this->url->setBaseUri('http://' . $_SERVER['SERVER_NAME'] . '/');

        $this->getRequestSetVars(['region']);

        // Instantiate Region Object.
        $regionObj              = new Region;

        $this->view->setVar('regionList', $regionObj->getPairs());
        $this->view->setVar('region', $this->region);
    }

    /**
     * @param $pageTitle
     * @param $brandTitle
     */
    protected function setViewTitle($pageTitle, $brandTitle)
    {
        $this->view->setVars([
            'pageTitle'             => $pageTitle,
            'brandTitle'            => $brandTitle
        ]);

    }

    /**
     * Using an array of request path/param names, get and set to internal class variables.
     *
     * @param       array       $requestList
     * @return      void
     */
    protected function getRequestSetVars(Array $requestList)
    {
        foreach ($requestList as $requestName) {
            $value                  = $this->dispatcher->getParam($requestName);

            if (empty($value)) {
                $value              = $this->request->get($requestName);
            }

            $this->$requestName     = $value;
        }
    }
}
