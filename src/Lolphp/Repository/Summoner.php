<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp\Repository;

use Lolphp\Entity\Summoner as SummonerEntity;

class Summoner extends RepositoryAbstract
{
    const CACHETTL_SUMMONER                 = 600; // 10 minutes.
    const CACHEPREFIX_SUMMONER              = 'summoner';
    const CRITERIA_SUMMONERNAME             = 'summonerNames';
    const CRITERIA_SUMMONERID               = 'summonerIds';
    const CRITERIA_REGION                   = 'region';

    const METHODNAME_BYNAME                 = 'by-name';

    /**
     * Returns Entities for Summoner based on name and ID search.
     *
     * Records are cached.
     *
     * @param array $criteria
     * @param array $orderBy
     * @param null $limit
     * @param null $offset
     * @return array|\Lolphp\Entity\EntityInterface
     */
    public function findBy(Array $criteria, Array $orderBy = null, $limit = null, $offset = null)
    {
        // Grab the cache.
        $cache                              = $this->entityManager->getConfiguration()->getCache();
        $cacheKeyPrefix                     = $this::CACHEPREFIX_SUMMONER . '.';

        $summonerNames                      = @($criteria[$this::CRITERIA_SUMMONERNAME] ?: null);
        $summonerIds                        = @($criteria[$this::CRITERIA_SUMMONERID] ?: null);

        $outputList                 = [];

        // Handle the cache.
        if (!is_array($summonerNames)) {
            $summonerNames          = [$summonerNames];
        }

        if (is_array($summonerNames)) {
            foreach ($summonerNames as $key => $name) {
                $cacheKey           = $cacheKeyPrefix . $name;
                if ($cache->exists($cacheKey)) {
                    $outputList[]   = $cache->get($cacheKey);
                    unset($summonerNames[$key]);
                }
            }
        }

        $connection                         = $this->entityManager->getConnection();
        $apiList                            = null;
        foreach ($criteria as $type => $search) {
            switch ($type) {
                case $this::CRITERIA_SUMMONERNAME:
                    // If cache pulled data, this may be empty.
                    if (empty($summonerNames)) {
                        break;
                    }

                    // Perform a call to api.
                    $apiList                = $this->entityManager->call(
                        [
                             $this::METHODNAME_BYNAME       => implode(',', $summonerNames)
                        ],
                        @($criteria[$this::CRITERIA_REGION] ?: $connection::REGION_NORTHAMERICA),
                        $connection::APIMETHOD_SUMMONER
                    );
                    break;
                case $this::CRITERIA_SUMMONERID:
                    break;
            }

            if (!empty($apiList)) {
                break;
            }
        }

        // Check if apiList is empty if cache pulled data.
        if (!empty($apiList)) {
            foreach ($apiList as $summonerName => $summonerData) {
                $summonerObj              = new SummonerEntity($summonerData);
                $cacheKey                 = $cacheKeyPrefix . $summonerObj->getName();
                $cache->save($cacheKey, $summonerObj, $this::CACHETTL_SUMMONER);
                $outputList[]             = $summonerObj;
            }
        }

        if (count($outputList) == 1) {
            return reset($outputList);
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $orderCol) {
                $outputList = $this->orderBy($outputList, $orderCol, -1);
            }
        }

        return $outputList;
    }
}
