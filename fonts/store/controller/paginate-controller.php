<?php

/**
 * Bridge shopping cart
 * Pagination class
 */
Class PaginateController
{

    /**
     * Constructor
     */
    public function __construct()
    {
        
    }

    /**
     * Create a pagination flow
     * @param int $perPage
     * @param array $result
     * @return array
     */
    public function setPagination($perPage, $result)
    {
        // Pagination
        $per_page      = $perPage;
        //$result = $products;
        $total_results = count($result);
        $total_pages   = ceil($total_results / $per_page); //total pages we going to have
        //-------------if page is set check------------------//
        $show_page     = 1;

        if (isset($_GET['pageNo']))
        {
            $show_page = $_GET['pageNo'];             //it will telles the current page
            if ($show_page > 0 && $show_page <= $total_pages)
            {
                $start = ($show_page - 1) * $per_page;
                $end   = $start + $per_page;
            }
            else
            {
                // error - show first set of results
                $start = 0;
                $end   = $per_page;
            }
        }
        else
        {
            // if page isn't set, show first set of results
            $start = 0;
            $end   = $per_page;
        }
        // display pagination
        $page = (isset($_GET['pageNo'])) ? intval($_GET['pageNo']) : 0;

        $tpages = $total_pages;
        if ($page <= 0)
            $page   = 1;

        return array('showPage' => $show_page, 'page' => $page, 'totalPages' => $total_pages, 'start' => $start, 'end' => $end);
    }

    /**
     * Paginate link logic implementation
     * @param string $reload
     * @param int $page
     * @param int $tpages
     * @return string
     */
    function paginate($reload, $page, $tpages)
    {

        $adjacents = 2;
        $prevlabel = "&lsaquo; Prev";
        $nextlabel = "Next &rsaquo;";
        $out       = "";

        // previous
        if ($page == 1)
        {
            $out.= "<span>" . $prevlabel . "</span>\n";
        }
        elseif ($page == 2)
        {
            $out.= "<li><a  href=\"" . $reload . "\">" . $prevlabel . "</a>\n</li>";
        }
        else
        {
            $out.= "<li><a  href=\"" . $reload . "&amp;pageNo=" . ($page - 1) . "\">" . $prevlabel . "</a>\n</li>";
        }

        $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
        $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
        for ($i = $pmin; $i <= $pmax; $i++)
        {
            if ($i == $page)
            {
                $out.= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
            }
            elseif ($i == 1)
            {
                $out.= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
            }
            else
            {
                $out.= "<li><a  href=\"" . $reload . "&amp;pageNo=" . $i . "\">" . $i . "</a>\n</li>";
            }
        }

        if ($page < ($tpages - $adjacents))
        {
            $out.= "<a style='font-size:11px' href=\"" . $reload . "&amp;pageNo=" . $tpages . "\">" . $tpages . "</a>\n";
        }
        // next
        if ($page < $tpages)
        {
            $out.= "<li><a  href=\"" . $reload . "&amp;pageNo=" . ($page + 1) . "\">" . $nextlabel . "</a>\n</li>";
        }
        else
        {
            $out.= "<span style='font-size:11px'>" . $nextlabel . "</span>\n";
        }
        $out.= "";
        return $out;
    }

}
