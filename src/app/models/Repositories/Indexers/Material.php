<?php
namespace Repositories\Indexers;

use Repositories\LocalRepository;

class Material implements IndexerInterface
{
    protected $database;

    const DEFAULT_INDEX = "materials";

    public function onNewObject(LocalRepository $repo, $object)
    {
        if ($object->type == "material") {
            $repo->append(self::DEFAULT_INDEX, $object->ident);
            $repo->set(self::DEFAULT_INDEX.".".$object->ident, $object->repo_id);
        }
    }

    public function onFinishedLoading(LocalRepository $repo)
    {
    }
}
