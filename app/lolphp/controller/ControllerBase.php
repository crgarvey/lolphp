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
        $assets->addJs('js/jquery-2.1.0.min.js');
        $assets->addJs('js/jquery-ui-1.9.2.custom.min.js');
        $assets->addJs('js/bootstrap.min.js');
        $assets->addJs('js/lolphp.js');

        $this->setViewTitle('League of Legends API', 'League of Legends API');
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
}
