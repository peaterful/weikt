<?php
namespace app\api\v1\model;

use think\Model;

class CurriculumClassification extends Model {

    public function getList ($where = [], $page = 1, $limit = 10, $fields = '*') {
        return $this->field($fields)
                    ->where($where)
                    ->page($page,$limit)
                    ->order('sort')
                    ->select();
    }

    public function getOne ($where, $fields = '*') {
        return $this->field($fields)
                    ->where($where)
                    ->find();
    }

    public function getChildList ($pid, $type = 1) {
        $sql = 'SELECT a.id,a.name,a.back_img,a.parent_id,if(b.num, b.num,0) as num FROM vcr_curriculum_classification a'
            . ' LEFT JOIN (SELECT cl_id, count(ust.id) AS num FROM vcr_curriculum cur'
            . ' LEFT JOIN vcr_curriculum_chapter cc ON cur.id  = cc.cp_id'
            . ' LEFT JOIN vcr_user_study ust ON ust.chapter_id = cc.id AND ust.state=1'
            . ' GROUP BY cur.cl_id) AS b ON b.cl_id = a.id'
            . ' WHERE a.parent_id = ' . $pid . ' AND a.level = 1';

        if ($type == 1) {
            $sql .= ' AND a.state = 1';
        }

        $sql .= ' ORDER BY a.sort';

        return $this->query($sql);
    }
}