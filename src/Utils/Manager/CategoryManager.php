<?php

namespace App\Utils\Manager;

use App\Entity\Category;
use Doctrine\Persistence\ObjectRepository;

class CategoryManager extends AbstractBaseManager
{

    /**
     * @return ObjectRepository
     */
    public function getRepository(): ObjectRepository
    {
        return $this->entityManager->getRepository(Category::class);
    }

    /**
     * @param Category $category
     */
    public function remove(object $category)
    {
        $category->setIsDeleted(true);

        foreach ($category->getProducts()->getValues() as $product) {
            $product->setIsDeleted(true);
        }

        $this->save($category);
    }
}