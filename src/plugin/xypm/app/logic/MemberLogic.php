<?php
// +----------------------------------------------------------------------
// | saiadmin [ saiadmin快速开发框架 ]
// +----------------------------------------------------------------------
// | Author: your name
// +----------------------------------------------------------------------
namespace plugin\xypm\app\logic;

use Exception;
use plugin\saiadmin\basic\BaseLogic;
use plugin\saiadmin\exception\ApiException;
use plugin\xypm\app\logic\build\AreaLogic;
use plugin\xypm\app\logic\build\BuildLogic;
use plugin\xypm\app\logic\build\GarageLogic;
use plugin\xypm\app\logic\build\HouseLogic;
use plugin\xypm\app\logic\build\ParkingLogic;
use plugin\xypm\app\logic\build\ShopLogic;
use plugin\xypm\app\model\admin\Member as MemberModel;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use think\db\exception\PDOException;

/**
 * 户主管理逻辑层
 */
class MemberLogic extends BaseLogic
{

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->model = new MemberModel();

    }



    public function search(array $searchWhere = []): mixed
    {
        $withSearch = array_keys($searchWhere);
        $data = $searchWhere;
        foreach ($withSearch as $k => $v) {
            if ($data[$v] === '') {
                unset($data[$v]);
                unset($withSearch[$k]);
            }
        }
        return $this->model->withSearch($withSearch, $data);
    }

    /**
     * 导入数据
     */
    public function import($type,$file)
    {
        $path = $this->getImport($file);
//        $reader = new Reader();

        //实例化reader
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        if (!in_array($ext, ['csv', 'xls', 'xlsx'])) {
            throw new ApiException(trans('Unknown data format'));
        }
        if ($ext === 'xls') {
            $reader = new Xls();
        } else {
            $reader = new Xlsx();
        }

        $fieldArr = [
            "姓名"=> "realname",
            "手机"=>"mobile",
            "身份证号"=>"idcard",
            "开始收费日期"=>"billdate",
            "备注"=>"remark"
        ];

        if($type == 'house'){
            $fieldArr["楼宇名称"] = "buildname";
            $fieldArr["单元"] = "unit";
            $fieldArr["楼层"] = "floor";
            $fieldArr["房号"] = "number";
            $fieldArr["建筑面积"] = "buildarea";
        }

        if($type == 'shop'){
            $fieldArr["商铺区域"] = "area";
            $fieldArr["所在楼层"] = "floor";
            $fieldArr["商铺编号"] = "number";
            $fieldArr["建筑面积"] = "buildarea";
        }

        if($type == 'parking'){
            $fieldArr["车位区域"] = "area";
            $fieldArr["车位编号"] = "number";
        }

        if($type == 'garage'){
            $fieldArr["储物间编号"] = "number";
            $fieldArr["建筑面积"] = "buildarea";
        }
        //加载文件
        $insert = [];
        try {
            if (!$PHPExcel = $reader->load($path)) {
                throw new ApiException(('Unknown data format'));
            }
            $currentSheet = $PHPExcel->getSheet(0);  //读取文件中的第一个工作表
            $allColumn = $currentSheet->getHighestDataColumn(); //取得最大的列号
            $allRow = $currentSheet->getHighestRow(); //取得一共有多少行
            $maxColumnNumber = Coordinate::columnIndexFromString($allColumn);
            $fields = [];
            for ($currentRow = 1; $currentRow <= 1; $currentRow++) {
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $fields[] = $val;
                }
            }

            for ($currentRow = 2; $currentRow <= $allRow; $currentRow++) {
                $values = [];
                for ($currentColumn = 1; $currentColumn <= $maxColumnNumber; $currentColumn++) {
                    $val = $currentSheet->getCellByColumnAndRow($currentColumn, $currentRow)->getValue();
                    $values[] = is_null($val) ? '' : $val;
                }
                $row = [];
                $temp = array_combine($fields, $values);
                foreach ($temp as $k => $v) {
                    if (isset($fieldArr[$k]) && $k !== '') {
                        $row[$fieldArr[$k]] = $v;
                    }
                }
                if ($row) {
                    $insert[] = $row;
                }
            }
        } catch (Exception $exception) {
            throw new ApiException($exception->getMessage());
        }

        if (!$insert) {
            throw new ApiException(('No rows were updated'));
        }

        if(count($insert) > 0 && count($insert[0]) != 10 && $type == 'house'){
            throw new ApiException(('数据格式错误，请参考模板导入'));
        }

        if(count($insert) > 0 && count($insert[0]) != 9 && $type == 'shop'){
            throw new ApiException(('数据格式错误，请参考模板导入'));
        }

        if(count($insert) > 0 && count($insert[0]) != 7 && ($type == 'parking'||$type=='garage')){
            throw new ApiException(trans('数据格式错误，请参考模板导入'));
        }
        try {
            $reader->open($path);
            try {
                $data = [];
                foreach ($insert as $item) {
                    $tempArr = [
                        "realname" => $item['realname'],
                        "mobile" => $item['mobile'],
                        "idcard" => $item['idcard'],
                        "billdate" => $item['billdate'],
                        'buildtype' => $type,
                        'remark' => $item['remark']
                    ];
                    if ($type == 'house') {
                        $build = BuildLogic::where(['name' => $item['buildname']])->find();
                        if (!empty($build)) {
                            $house = HouseLogic::where(['build_id' => $build['id'], 'unit' => $item['unit'], 'floor' => $item['floor'], 'number' => $item['number']])->find();
                            if (!empty($house)) {
                                $house->save([
                                    "ownername" => $item['realname'],
                                    "mobile" => $item['mobile'],
                                    'buildarea' => $item['buildarea'],
                                    'billdate' => $item['billdate'],
                                    'status' => 1
                                ]);
                                $member = (new MemberModel)->where(['build_id' => $house['id'], 'buildtype' => 'house', 'identity' => 1])->find();
                                if (empty($member)) {
                                    $tempArr['build_id'] = $house['id'];
                                    $tempArr['buildarea'] = $item['buildarea'];
                                    $tempArr['buildname'] = $build['name'] . $item['unit'] . '单元' . $item['number'];
                                    $data[] = $tempArr;
                                } else {
                                    $tempArr['buildarea'] = $item['buildarea'];
                                    $member->save($tempArr);
                                }
                            }
                        }
                    }

                    if ($type == 'shop') {
                        $area = AreaLogic::where(['name' => $item['area'], 'type' => 'shop'])->find();
                        if (!empty($area)) {
                            $shop = ShopLogic::where(['build_area_id' => $area['id'], 'number' => $item['number']])->find();
                            if (!empty($shop)) {
                                $shop->save([
                                    "ownername" => $item['realname'],
                                    "mobile" => $item['mobile'],
                                    'buildarea' => $item['buildarea'],
                                    'billdate' => $item['billdate'],
                                    'status' => 1
                                ]);
                                $member =  (new MemberModel)->where(['build_id' => $shop['id'], 'buildtype' => 'shop', 'identity' => 1])->find();
                                if (empty($member)) {
                                    $tempArr['build_id'] = $shop['id'];
                                    $tempArr['buildarea'] = $item['buildarea'];
                                    $tempArr['buildname'] = $area['name'] . '-' . $item['number'];
                                    $data[] = $tempArr;
                                } else {
                                    $tempArr['buildarea'] = $item['buildarea'];
                                    $member->save($tempArr);
                                }
                            }
                        }
                    }

                    if ($type == 'parking') {
                        $area = AreaLogic::where(['name' => $item['area'], 'type' => 'parking'])->find();

                        if (!empty($area)) {
                            $parking = ParkingLogic::where(['build_area_id' => $area['id'], 'number' => $item['number']])->find();
                            if (!empty($parking)) {
                                $parking->save([
                                    "ownername" => $item['realname'],
                                    "mobile" => $item['mobile'],
                                    'billdate' => $item['billdate'],
                                    'status' => 1
                                ]);
                                $member =  (new MemberModel)->where(['build_id' => $parking['id'], 'buildtype' => 'parking', 'identity' => 1])->find();
                                if (empty($member)) {
                                    $tempArr['build_id'] = $parking['id'];
                                    $tempArr['buildname'] = $area['name'] . '-' . $item['number'];
                                    $data[] = $tempArr;
                                } else {
                                    $member->save($tempArr);
                                }
                            }
                        }
                    }

                    if ($type == 'garage') {

                        $garage = GarageLogic::where(['number' => $item['number']])->find();
                        if (!empty($garage)) {
                            $garage->save([
                                "ownername" => $item['realname'],
                                "mobile" => $item['mobile'],
                                'buildarea' => $item['buildarea'],
                                'billdate' => $item['billdate'],
                                'status' => 1
                            ]);
                            $member =  (new MemberModel)->where(['build_id' => $garage['id'], 'buildtype' => 'garage', 'identity' => 1])->find();
                            if (empty($member)) {
                                $tempArr['build_id'] = $garage['id'];
                                $tempArr['buildname'] = $item['number'];
                                $tempArr['buildarea'] = $item['buildarea'];
                                $data[] = $tempArr;
                            } else {
                                $member->save($tempArr);
                            }
                        }
                    }
                }
                $this->model->saveAll($data);

            } catch (PDOException $exception) {
                $msg = $exception->getMessage();
                if (preg_match("/.+Integrity constraint violation: 1062 Duplicate entry '(.+)' for key '(.+)'/is", $msg, $matches)) {
                    $msg = "导入失败，包含【{$matches[1]}】的记录已存在";
                };
                throw new ApiException($msg);
            } 
        } catch (Exception $e) {
            throw new ApiException($e->getMessage());
        }
        


    }
    public function getBuildTypeList(){



        return (new MemberModel)->getBuildTypeList();
    }

}
