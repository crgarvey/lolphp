<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Controller;

use Lolphp\Repository\Summoner as SummonerRepository;
use Lolphp\Entity\Summoner as SummonerEntity;

class SummonerController extends ControllerBase
{
    protected $term;

    public function initialize()
    {
        $requestList                = ['term', 'region'];

        foreach ($requestList as $requestName) {
            $value                  = $this->dispatcher->getParam($requestName);

            if (empty($value)) {
                $value              = $this->request->get($requestName);
            }

            $this->$requestName     = $value;
        }

        /*
        $term               = $this->dispatcher->getParam('term');
        if (empty($term)) {
            $term           = $this->request->get('term');
        }

        $this->term         = $term;
        */
        parent::initialize();
    }

    public function searchAction()
    {
        $term               = $this->term;
        $term               = explode(',', $term);
        $region             = $this->region;

        /**
         * @var SummonerRepository $repo
         * @var SummonerEntity $s
         */
        $repo       = $this->em->getRepository(new SummonerEntity());

        if ($term !== null) {
            try {
                if (is_numeric($term)) {
                    $summonerList   = $repo->findBy([
                        $repo::CRITERIA_SUMMONERID => $term,
                        $repo::CRITERIA_REGION     => $region
                    ]);
                } else {
                    $summonerList   = $repo->findBy([
                        $repo::CRITERIA_SUMMONERNAME => $term,
                        $repo::CRITERIA_REGION       => $region
                    ]);
                }
            } catch (\Exception $e) {
                exit;
            }

            // Retrieve entire cache list of summoners. If close matches, combine them.
            $summonerCacheList      = $repo->findAll();

            if ($summonerCacheList !== null) {
                foreach ($summonerCacheList as $s) {
                    if (in_array($s, $summonerList)) {
                        continue;
                    }

                    // Check the region.
                    if (stripos($s->getRegion(), $region) === false) {
                        continue;
                    }

                    // Check the term.
                    foreach ($term as $t) {
                        if (stripos($s->getName(), $t) !== false) {
                            $summonerList[]     = $s;
                        } elseif (stripos($s->getId(), $t) !== false) {
                            $summonerList[]     = $s;
                        }
                    }
                }
            }

            if (empty($summonerList)) {
                exit;
            }

            $resultList     = [];
            foreach ($summonerList as $s) {
                array_push($resultList, [
                    'value'            => $s->getId(),
                    'label'            => $s->getName(),
                    'region'           => $region
                ]);
            }

            echo json_encode($resultList);
            exit;
        }
    }

    public function profileAction()
    {
        $term           = $this->term;
        $region         = $this->region;

        /**
         * @var SummonerRepository $repo
         * @var SummonerEntity $summoner
         */
        $repo           = $this->em->getRepository(new SummonerEntity());
        try {
            if (is_numeric($term)) {
                $summoner       = $repo->find((int) $term);
            } else {
                $summoner       = $repo->findBy([
                    $repo::CRITERIA_SUMMONERNAME   => $term,
                    $repo::CRITERIA_REGION         => $region
                ]);
                $summoner       = $summoner[0];
            }
        } catch (\Exception $e) {
            $this->dispatcher->forward([
                'controller'    => 'index',
                'action'        => 'index'
            ]);
        }

        $this->view->setVar('summoner', $summoner);
    }
}