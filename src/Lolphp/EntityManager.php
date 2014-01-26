<?php
/**
 * Created for Lolphp on 1/25/14.
 *
 * @author Robbie Vaughn <robbie@robbievaughn.me>
 */
namespace Lolphp;

use Lolphp\Repository\RepositoryInterface;
use Lolphp\Entity\EntityInterface;

class EntityManager implements EntityManagerInterface
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @param Connection $connection
     * @param Configuration $configuration
     */
    public function __construct(Connection $connection, Configuration $configuration)
    {
        $this->connection    = $connection;
        $this->configuration = $configuration;
    }

    /**
     * @param EntityInterface $entity
     *
     * @return RepositoryInterface
     */
    public function getRepository(EntityInterface $entity)
    {
        return $this->configuration->getRepositoryFactory()->getRepository($this, $entity);
    }

    /**
     * @return Configuration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @param array $request
     * @param string $region
     * @param mixed $method
     * @param array $fields
     * @param null|string $verb
     * @return mixed|\StdClass
     */
    public function call(
        Array $request = [],
        $region = Connection::REGION_NORTHAMERICA,
        $method = '',
        Array $fields = [],
        $verb = Connection::VERB_GET
    ) {
        $connection = $this->getConnection();

        if (is_array($method)) {
            $apiParamType  = $method[1];
            $method        = $method[0];
        } else {
            $apiParamType      = $connection::APIMETHOD_PARAMTYPE_PREPEND;
        }

        // Format request properly; region/method/methodVersion.
        $requestUrl        = $region
            . '/'
            . $connection->apiMethodVersionList[$method]
            . '/'
            . $method
            . '/'
            ;

        $firstRequest       = false;
        foreach ($request as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }

            // Build the URL.
            if (!$firstRequest) {
                $firstRequest = true;

                switch ($apiParamType) {
                    case $connection::APIMETHOD_PARAMTYPE_APPEND:
                        $requestUrl     .= $value . '/' . $key;
                        break;

                    case $connection::APIMETHOD_PARAMTYPE_PREPEND:
                        $requestUrl     .= $key . '/' . $value;
                        break;
                }
            } else {
                $requestUrl     .= $key . '/' . $value;
            }
        }

        return $connection->call($requestUrl, $fields);
    }
}