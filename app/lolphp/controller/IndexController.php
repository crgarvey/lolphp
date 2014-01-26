<?php
/**
 * Created for Lolphp on 1/26/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Controller;

use Lolphp\EntityManager;
use Lolphp\Repository\Summoner as SummonerRepository;
use Lolphp\Entity\Summoner as SummonerEntity;

class IndexController extends ControllerBase
{
    public function indexAction()
    {

    }

    public function searchAction()
    {
        $term       = strtolower($this->request->get('term', null, null));
        $term       = explode(',', $term);

        /**
         * @var SummonerRepository $repo
         * @var SummonerEntity $s
         */
        $repo       = $this->em->getRepository(new SummonerEntity());

        if ($term !== null) {
            try {
                if (is_numeric($term)) {
                    $summonerList   = $repo->findBy([$repo::CRITERIA_SUMMONERID => $term]);
                } else {
                    $summonerList   = $repo->findBy([$repo::CRITERIA_SUMMONERNAME => $term]);
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
                    'label'          => $s->getName()
                ]);
            }

            echo json_encode($resultList);
            exit;
        }
    }

    public function summonerAction()
    {
        $id     = $this->request->get('id');

        /**
         * @var SummonerRepository $repo
         * @var SummonerEntity $summoner
         */
        $repo           = $this->em->getRepository(new SummonerEntity());
        try {
            $summoner       = $repo->find($id);
        } catch (\Exception $e) {
            $this->dispatcher->forward([
                'controller'    => 'index',
                'action'        => 'index'
            ]);
        }

        $this->view->setVar('summoner', $summoner);
    }
}