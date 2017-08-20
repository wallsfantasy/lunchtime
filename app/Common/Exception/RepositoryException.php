<?php

namespace App\Common\Exception;

final class RepositoryException extends \LogicException
{
    use ContextAwareTrait;

    const CODES = [
        'infrastructural' => 0,
        'get_not_found' => 1,
        'add_existing_entity' => 2,
    ];

    public function __construct($message = '', $code = 0, \Throwable $previous = null, array $context = [])
    {
        parent::__construct($message, $code, $previous);
        $this->context = $context;
    }

    public static function infrastructural(string $repositoryClass, string $entityClass, \Throwable $previous = null)
    {
        return new self(
            "Infrastructural or underlying library error/exception.",
            self::CODES['infrastructural'],
            $previous,
            [
                'repository_class' => $repositoryClass,
                'entity_class' => $entityClass,
            ]
        );
    }

    /**
     * @param string     $repositoryClass
     * @param string     $entityClass
     * @param int|string $id
     *
     * @return RepositoryException
     */
    public static function getNotFound(string $repositoryClass, string $entityClass, $id, \Throwable $previous = null)
    {
        return new self(
            "Entity ID:{$id} not found.",
            self::CODES['get_not_found'],
            $previous,
            [
                'repository_class' => $repositoryClass,
                'entity_class' => $entityClass,
                'id' => $id,
            ]
        );
    }

    /**
     * @param string          $repositoryClass
     * @param string          $entityClass
     * @param int|string      $id
     * @param array           $data
     * @param \Throwable|null $previous
     *
     * @return RepositoryException
     */
    public static function addAlreadyExistEntity(
        string $repositoryClass,
        string $entityClass,
        $id,
        array $data = [],
        \Throwable $previous = null
    ) {
        return new self(
            "Add already exist entity of ID:{$id}.",
            self::CODES['add_existing_entity'],
            $previous,
            [
                'repository_class' => $repositoryClass,
                'entity_class' => $entityClass,
                'id' => $id,
                'data' => $data,
            ]
        );
    }
}
