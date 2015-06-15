<?php

/**
 * @project Bridge shoppingcart
 * Manage categories
 */
include_once 'application-controller.php';

//include_once 'database-controller.php';

Class CategoryController extends AppController
{

    /**
     * Constructor
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all categories
     * @param int $categoryId
     * @return array
     */
    public function get($categoryId = null, $data = null)
    {
        if ($categoryId != null)
        {
            $categories = $this->database->category_by_id($categoryId);
        }
        else
        {
            $categories = $this->database->category_get_all( $data );
        }
        return $categories;
    }

    /**
     * Manage category insert
     * @param array $data
     */
    public function insert($data)
    {
        $result = $this->database->category_insert($data);
        return $result;
    }

    /**
     * Delete category
     * @param int $id
     * @return boolean
     */
    public function delete($id)
    {
        $result = $this->database->category_delete($id);
        return $result;
    }

}
