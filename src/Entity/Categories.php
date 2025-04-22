<?php
// src/Entity/Categories.php
namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Entity\Products;
/**
 * @ORM\Entity
 * @ORM\Table(name="categories")
 **/
class Categories implements \jsonSerializable
{
    /** @var int */
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private int $category_id;

    /** @var string */
    /**
     * @ORM\Column(type="string")
     */
    private string $category_name;

    /**
     * @ORM\OneToMany(targetEntity="Entity\Products", mappedBy="category")
     */
    private Collection $products;

    public function __construct(array $data = null)
    {
        // Initialiser la collection products
        $this->products = new ArrayCollection();

        // Si des données sont fournies, les assigner aux propriétés correspondantes
        if (!empty($data) && is_array($data)) {
            foreach ($data as $key => $value) {
                // Vérifier si la propriété existe et qu'on peut lui attribuer une valeur
                if (property_exists($this, $key)) {
                    $this->$key = $value;
                }
            }
        }
    }
    public function __tostring()
    {
        $s = "";
        foreach ($this as $k => $v) {
            if ($k != "products") {
                $s .= $k . ": " . $v . ", ";
            }
        }
        return $s;
    }

    /**
     * Get category_id
     *
     * @return int
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set category_id
     *
     * @param int $category_id
     * @return categories
     */
    public function setCategoryId($category_id)
    {
        $this->category_id = $category_id;
        return $this;
    }

    /**
     * Get category_name
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->category_name;
    }

    /**
     * Set category_name
     *
     * @param string $category_name
     * @return categories
     */
    public function setCategoryName($category_name)
    {
        $this->category_name = $category_name;
        return $this;
    }
    public function jsonSerialize() : mixed
    {
        return [
            'category_id' => $this->category_id,
            'category_name' => $this->category_name
            // Omettez products pour éviter les références circulaires
        ];
    }
}