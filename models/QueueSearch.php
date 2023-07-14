<?php

namespace app\models;

use app\components\Session;
use yii\data\ActiveDataProvider;

class QueueSearch extends Queue
{
    public function getQuerySearch($params)
    {
        $query = Queue::find();

        $this->load($params);

        return $query;
    }

    public function search($params)
    {
        $query = $this->getQuerySearch($params);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }
}
