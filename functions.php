<?php
/**
 * Created by PhpStorm.
 * User: IMkoba
 * Date: 2015/1/29
 * Time: 下午 2:01
 */

// define intervals for the data selection
function sqlInterval() {
    global $interval;
    switch ($interval) {
        case "hourly":
            return "DATE_FORMAT(t.created_at,'%Y-%m-%d %Hh') datepart ";
            break;
        case "weekly":
            return "DATE_FORMAT(t.created_at,'%Y %u') datepart ";
            break;
        case "monthly":
            return "DATE_FORMAT(t.created_at,'%Y-%m') datepart ";
            break;
        case "yearly":
            return "DATE_FORMAT(t.created_at,'%Y') datepart ";
            break;
        case "overall":
            return "DATE_FORMAT(t.created_at,'overall') datepart ";
            break;
        default:
            return "DATE_FORMAT(t.created_at,'%Y-%m-%d') datepart "; // default daily (also used for custom)
    }
}

// here further sqlSubset selection is constructed
function sqlSubset($where = NULL) {
    error_reporting(E_ALL);
    global $esc;
    $sql = "";
    if (!empty($esc['mysql']['url_query']) && strstr($where, "u.") == false)
        $sql .= ", " . $esc['mysql']['dataset'] . "_urls u";
    $sql .= " WHERE ";
    if (!empty($where))
        $sql .= $where;
    if (!empty($esc['mysql']['from_user_name'])) {
        if (strstr($esc['mysql']['from_user_name'], "AND") !== false) {
            $subqueries = explode(" AND ", $esc['mysql']['from_user_name']);
            foreach ($subqueries as $subquery) {
                $sql .= "LOWER(t.from_user_name) = LOWER('" . $subquery . "') AND ";
            }
        } elseif (strstr($esc['mysql']['from_user_name'], "OR") !== false) {
            $subqueries = explode(" OR ", $esc['mysql']['from_user_name']);
            $sql .= "(";
            foreach ($subqueries as $subquery) {
                $sql .= "LOWER(t.from_user_name) = LOWER('" . $subquery . "') OR ";
            }
            $sql = substr($sql, 0, -3) . ") AND ";
        } else {
            $sql .= "LOWER(t.from_user_name) = LOWER('" . $esc['mysql']['from_user_name'] . "') AND ";
        }
    }
    if (!empty($esc['mysql']['query'])) {
        if (strstr($esc['mysql']['query'], "AND") !== false) {
            $subqueries = explode(" AND ", $esc['mysql']['query']);
            foreach ($subqueries as $subquery) {
                $sql .= "LOWER(t.text) LIKE '%" . $subquery . "%' AND ";
            }
        } elseif (strstr($esc['mysql']['query'], "OR") !== false) {
            $subqueries = explode(" OR ", $esc['mysql']['query']);
            $sql .= "(";
            foreach ($subqueries as $subquery) {
                $sql .= "LOWER(t.text) LIKE '%" . $subquery . "%' OR ";
            }
            $sql = substr($sql, 0, -3) . ") AND ";
        } else {
            $sql .= "LOWER(t.text) LIKE '%" . $esc['mysql']['query'] . "%' AND ";
        }
    }
    if (!empty($esc['mysql']['url_query'])) {
        if (strstr($where, "u.") == false)
            $sql .= " u.tweet_id = t.id AND ";
        if (strstr($esc['mysql']['url_query'], "AND") !== false) {
            $subqueries = explode(" AND ", $esc['mysql']['url_query']);
            foreach ($subqueries as $subquery) {
                $sql .= "(";
                $sql .= "(LOWER(u.url_followed) LIKE '%" . $subquery . "%') OR ";
                $sql .= "(LOWER(u.url_expanded) LIKE '%" . $subquery . "%')";
                $sql .= ")";
                $sql .= " AND ";
            }
        } elseif (strstr($esc['mysql']['url_query'], "OR") !== false) {
            $subqueries = explode(" OR ", $esc['mysql']['url_query']);
            $sql .= "(";
            foreach ($subqueries as $subquery) {
                $sql .= "(";
                $sql .= "(LOWER(u.url_followed) LIKE '%" . $subquery . "%') OR ";
                $sql .= "(LOWER(u.url_expanded) LIKE '%" . $subquery . "%')";
                $sql .= ")";
                $sql .= " OR ";
            }
            $sql = substr($sql, 0, -3) . ") AND ";
        } else {
            $subquery = $esc['mysql']['url_query'];
            $sql .= "(";
            $sql .= "(LOWER(u.url_followed) LIKE '%" . $subquery . "%') OR ";
            $sql .= "(LOWER(u.url_expanded) LIKE '%" . $subquery . "%')";
            $sql .= ") AND ";
        }
    }
    if (!empty($esc['mysql']['geo_query']) && dbserver_has_geo_functions()) {

        $polygon = "POLYGON((" . $esc['mysql']['geo_query'] . "))";

        $polygonfromtext = "GeomFromText('" . $polygon . "')";
        $pointfromtext = "PointFromText(CONCAT('POINT(',t.geo_lng,' ',t.geo_lat,')'))";

        $sql .= " ( t.geo_lat != '0.00000' and t.geo_lng != '0.00000' and ST_Contains(" . $polygonfromtext . ", " . $pointfromtext . ") ";

        $sql .= " ) AND ";
    }
    if (!empty($esc['mysql']['from_source'])) {
        $sql .= "LOWER(t.source) LIKE '%" . $esc['mysql']['from_source'] . "%' AND ";
    }
    if (!empty($esc['mysql']['exclude'])) {
        if (strstr($esc['mysql']['exclude'], "AND") !== false) {
            $subqueries = explode(" AND ", $esc['mysql']['exclude']);
            foreach ($subqueries as $subquery) {
                $sql .= "LOWER(t.text) NOT LIKE '%" . $subquery . "%' AND ";
            }
        } elseif (strstr($esc['mysql']['exclude'], "OR") !== false) {
            $subqueries = explode(" OR ", $esc['mysql']['exclude']);
            $sql .= "(";
            foreach ($subqueries as $subquery) {
                $sql .= "LOWER(t.text) NOT LIKE '%" . $subquery . "%' OR ";
            }
            $sql = substr($sql, 0, -3) . ") AND ";
        } else {
            $sql .= "LOWER(t.text) NOT LIKE '%" . $esc['mysql']['exclude'] . "%' AND ";
        }
    }
    if (!empty($esc['mysql']['from_user_lang'])) {
        if (strstr($esc['mysql']['from_user_lang'], "AND") !== false) {
            $subqueries = explode(" AND ", $esc['mysql']['from_user_lang']);
            foreach ($subqueries as $subquery) {
                $sql .= "from_user_lang = '" . $subquery . "' AND ";
            }
        } elseif (strstr($esc['mysql']['from_user_lang'], "OR") !== false) {
            $subqueries = explode(" OR ", $esc['mysql']['from_user_lang']);
            $sql .= "(";
            foreach ($subqueries as $subquery) {
                $sql .= "from_user_lang = '" . $subquery . "' OR ";
            }
            $sql = substr($sql, 0, -3) . ") AND ";
        } else {
            $sql .= "from_user_lang = '" . $esc['mysql']['from_user_lang'] . "' AND ";
        }
    }
    $sql .= " t.created_at >= '" . $esc['datetime']['startdate'] . "' AND t.created_at <= '" . $esc['datetime']['enddate'] . "' ";
    //print $sql."<br>"; die;
    return $sql;
}