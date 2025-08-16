    -- 根级菜单: 智慧小区物业管理
    INSERT INTO `sa_system_menu` VALUES (NULL, 0, '0', '智慧小区物业管理', 'xypm', 'IconMobile', '/app/xypm', '', NULL, 2, 1, 'M', 0, NULL, 1, 11, '', 1, 1, now(), now(), NULL);
    SET @id := LAST_INSERT_ID();
    SET @root_id := LAST_INSERT_ID();
    SET @root_level := CONCAT('0', ',', @root_id);

    -- 一级菜单: 控制台
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '控制台', 'xypm/dashboard/index', 'IconMenu', 'xypm/dashboard/index', 'xypm/dashboard/index', NULL, 2, 1, 'M', 20, NULL, 1, 1, '', 1, 1, now(), now(), NULL);

    -- 一级菜单: 房产管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '房产管理', 'xypm/build/build', 'IconMenu', 'app/xypm/build/build', '', NULL, 2, 1, 'M', 19, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @build_id := LAST_INSERT_ID();
    SET @build_level := CONCAT(@root_level, ',', @build_id);

    -- 房产管理的子菜单: 区域管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @build_id, @build_level, '区域管理', 'xypm/build/area/index', 'IconMenu', 'app/xypm/build/area/index', 'xypm/build/area/index', NULL, 2, 1, 'M', 11, NULL, 1, 0, '', 1, 1, now(), now(), NULL);
    SET @area_id := LAST_INSERT_ID();
    SET @area_level := CONCAT(@build_level, ',', @area_id);

    -- 区域管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @area_id, @area_level, '批量更新', 'xypm/build/area/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @area_id, @area_level, '添加', 'xypm/build/area/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @area_id, @area_level, '编辑', 'xypm/build/area/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @area_id, @area_level, '删除', 'xypm/build/area/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 房产管理的子菜单: 楼宇管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @build_id, @build_level, '楼宇管理', 'xypm/build/build/index', 'IconMenu', 'app/xypm/build/build/index', 'xypm/build/build/index', NULL, 2, 1, 'M', 10, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @building_id := LAST_INSERT_ID();
    SET @building_level := CONCAT(@build_level, ',', @building_id);

    -- 楼宇管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @building_id, @building_level, '批量添加', 'xypm/build/build/multiadd', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multiadd', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @building_id, @building_level, '选择', 'xypm/build/build/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @building_id, @building_level, '添加', 'xypm/build/build/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @building_id, @building_level, '编辑', 'xypm/build/build/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @building_id, @building_level, '删除', 'xypm/build/build/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 房产管理的子菜单: 房屋管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @build_id, @build_level, '房屋管理', 'xypm/build/house/index', 'IconMenu', 'app/xypm/build/house/index', 'xypm/build/house/index', NULL, 2, 1, 'M', 9, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @house_id := LAST_INSERT_ID();
    SET @house_level := CONCAT(@build_level, ',', @house_id);

    -- 房屋管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_id, @house_level, '批量添加', 'xypm/build/house/multiadd', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multiadd', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_id, @house_level, '选择', 'xypm/build/house/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_id, @house_level, '添加', 'xypm/build/house/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_id, @house_level, '编辑', 'xypm/build/house/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_id, @house_level, '删除', 'xypm/build/house/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 房产管理的子菜单: 商铺管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @build_id, @build_level, '商铺管理', 'xypm/build/shop/index', 'IconMenu', 'app/xypm/build/shop/index', 'xypm/build/shop/index', NULL, 2, 1, 'M', 8, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @shop_id := LAST_INSERT_ID();
    SET @shop_level := CONCAT(@build_level, ',', @shop_id);

    -- 商铺管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_id, @shop_level, '批量添加', 'xypm/build/shop/multiadd', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multiadd', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_id, @shop_level, '选择', 'xypm/build/shop/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_id, @shop_level, '添加', 'xypm/build/shop/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_id, @shop_level, '编辑', 'xypm/build/shop/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_id, @shop_level, '删除', 'xypm/build/shop/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 房产管理的子菜单: 车位管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @build_id, @build_level, '车位管理', 'xypm/build/parking/index', 'IconMenu', 'app/xypm/build/parking/index', 'xypm/build/parking/index', NULL, 2, 1, 'M', 7, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @parking_id := LAST_INSERT_ID();
    SET @parking_level := CONCAT(@build_level, ',', @parking_id);

    -- 车位管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_id, @parking_level, '批量添加', 'xypm/build/parking/multiadd', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multiadd', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_id, @parking_level, '选择', 'xypm/build/parking/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_id, @parking_level, '添加', 'xypm/build/parking/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_id, @parking_level, '编辑', 'xypm/build/parking/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_id, @parking_level, '删除', 'xypm/build/parking/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 房产管理的子菜单: 储物间管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @build_id, @build_level, '储物间管理', 'xypm/build/garage/index', 'IconMenu', 'app/xypm/build/garage/index', 'xypm/build/garage/index', NULL, 2, 1, 'M', 6, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @garage_id := LAST_INSERT_ID();
    SET @garage_level := CONCAT(@build_level, ',', @garage_id);

    -- 储物间管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_id, @garage_level, '批量添加', 'xypm/build/garage/multiadd', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multiadd', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_id, @garage_level, '选择', 'xypm/build/garage/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_id, @garage_level, '添加', 'xypm/build/garage/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_id, @garage_level, '编辑', 'xypm/build/garage/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_id, @garage_level, '删除', 'xypm/build/garage/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 户主管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '户主管理', 'xypm/member/index', 'IconMenu', 'app/xypm/member/index', 'xypm/member/index', NULL, 2, 1, 'M', 18, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @member_id := LAST_INSERT_ID();
    SET @member_level := CONCAT(@root_level, ',', @member_id);

    -- 户主管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '导入', 'xypm/member/import', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'import', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '添加', 'xypm/member/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '编辑', 'xypm/member/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '删除', 'xypm/member/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '成员管理', 'xypm/member/family', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'family', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '添加成员', 'xypm/member/addfamily', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'addfamily', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '解绑会员', 'xypm/member/unbind', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'unbind', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 户主管理的子菜单: 房屋户主
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '房屋户主', 'xypm/member/house', 'IconMenu', 'app/xypm/member/house/index', 'xypm/member/house/index', NULL, 2, 1, 'M', 20, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @house_owner_id := LAST_INSERT_ID();
    SET @house_owner_level := CONCAT(@member_level, ',', @house_owner_id);

    -- 房屋户主的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_owner_id, @house_owner_level, '添加', 'xypm/member/add/type/house', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_owner_id, @house_owner_level, '编辑', 'xypm/member/edit/type/house', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_owner_id, @house_owner_level, '删除', 'xypm/member/destroy/type/house', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_owner_id, @house_owner_level, '导入', 'xypm/member/import/type/house', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'import', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 户主管理的子菜单: 商铺户主
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '商铺户主', 'xypm/member/shop', 'IconMenu', 'app/xypm/member/shop/index', 'xypm/member/shop/index', NULL, 2, 1, 'M', 19, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @shop_owner_id := LAST_INSERT_ID();
    SET @shop_owner_level := CONCAT(@member_level, ',', @shop_owner_id);

    -- 商铺户主的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_owner_id, @shop_owner_level, '添加', 'xypm/member/add/type/shop', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_owner_id, @shop_owner_level, '编辑', 'xypm/member/edit/type/shop', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_owner_id, @shop_owner_level, '删除', 'xypm/member/destroy/type/shop', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_owner_id, @shop_owner_level, '导入', 'xypm/member/import/type/shop', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'import', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 户主管理的子菜单: 车位户主
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '车位户主', 'xypm/member/parking', 'IconMenu', 'app/xypm/member/parking/index', 'xypm/member/parking/index', NULL, 2, 1, 'M', 18, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @parking_owner_id := LAST_INSERT_ID();
    SET @parking_owner_level := CONCAT(@member_level, ',', @parking_owner_id);

    -- 车位户主的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_owner_id, @parking_owner_level, '添加', 'xypm/member/add/type/parking', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_owner_id, @parking_owner_level, '编辑', 'xypm/member/edit/type/parking', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_owner_id, @parking_owner_level, '删除', 'xypm/member/destroy/type/parking', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_owner_id, @parking_owner_level, '导入', 'xypm/member/import/type/parking', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'import', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 户主管理的子菜单: 储物间户主
    INSERT INTO `sa_system_menu` VALUES (NULL, @member_id, @member_level, '储物间户主', 'xypm/member/garage', 'IconMenu', 'app/xypm/member/garage/index', 'xypm/member/garage/index', NULL, 2, 1, 'M', 17, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @garage_owner_id := LAST_INSERT_ID();
    SET @garage_owner_level := CONCAT(@member_level, ',', @garage_owner_id);

    -- 储物间户主的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_owner_id, @garage_owner_level, '添加', 'xypm/member/add/type/garage', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_owner_id, @garage_owner_level, '编辑', 'xypm/member/edit/type/garage', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_owner_id, @garage_owner_level, '删除', 'xypm/member/destroy/type/garage', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_owner_id, @garage_owner_level, '导入', 'xypm/member/import/type/garage', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'import', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 物业服务
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '物业服务', 'xypm/service', 'IconMenu', 'xypm/service', '', NULL, 2, 1, 'M', 17, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @service_id := LAST_INSERT_ID();
    SET @service_level := CONCAT(@root_level, ',', @service_id);

    -- 物业服务的子菜单: 通知公告
    INSERT INTO `sa_system_menu` VALUES (NULL, @service_id, @service_level, '通知公告', 'xypm/notice/index', 'IconMenu', 'app/xypm/notice/index', 'xypm/notice/index', NULL, 2, 1, 'M', 20, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @notice_id := LAST_INSERT_ID();
    SET @notice_level := CONCAT(@service_level, ',', @notice_id);

    -- 通知公告的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '添加', 'xypm/notice/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '编辑', 'xypm/notice/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '删除', 'xypm/notice/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '批量更新', 'xypm/notice/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '回收站', 'xypm/notice/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '还原', 'xypm/notice/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @notice_id, @notice_level, '真实删除', 'xypm/notice/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 物业服务的子菜单: 业主报修
    INSERT INTO `sa_system_menu` VALUES (NULL, @service_id, @service_level, '业主报修', 'xypm/repair/index', 'IconMenu', 'app/xypm/repair/index', 'xypm/repair/index', NULL, 2, 1, 'M', 19, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @repair_id := LAST_INSERT_ID();
    SET @repair_level := CONCAT(@service_level, ',', @repair_id);

    -- 业主报修的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @repair_id, @repair_level, '详情', 'xypm/repair/detail', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'detail', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @repair_id, @repair_level, '删除', 'xypm/repair/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @repair_id, @repair_level, '批量更新', 'xypm/repair/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @repair_id, @repair_level, '回收站', 'xypm/repair/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @repair_id, @repair_level, '还原', 'xypm/repair/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @repair_id, @repair_level, '真实删除', 'xypm/repair/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 物业服务的子菜单: 投诉建议
    INSERT INTO `sa_system_menu` VALUES (NULL, @service_id, @service_level, '投诉建议', 'xypm/suggest/index', 'IconMenu', 'app/xypm/suggest/index', 'xypm/suggest/index', NULL, 2, 1, 'M', 18, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @suggest_id := LAST_INSERT_ID();
    SET @suggest_level := CONCAT(@service_level, ',', @suggest_id);

    -- 投诉建议的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @suggest_id, @suggest_level, '详情', 'xypm/suggest/detail', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'detail', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @suggest_id, @suggest_level, '删除', 'xypm/suggest/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @suggest_id, @suggest_level, '批量更新', 'xypm/suggest/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @suggest_id, @suggest_level, '回收站', 'xypm/suggest/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @suggest_id, @suggest_level, '还原', 'xypm/suggest/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @suggest_id, @suggest_level, '真实删除', 'xypm/suggest/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 物业服务的子菜单: 小区活动
    INSERT INTO `sa_system_menu` VALUES (NULL, @service_id, @service_level, '小区活动', 'xypm/activity/activity/index', 'IconMenu', 'app/xypm/activity/activity/index', 'xypm/activity/activity/index', NULL, 2, 1, 'M', 17, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @activity_id := LAST_INSERT_ID();
    SET @activity_level := CONCAT(@service_level, ',', @activity_id);

    -- 小区活动的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '活动报名', 'xypm/activity/signup/index', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'signup', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '添加', 'xypm/activity/activity/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '编辑', 'xypm/activity/activity/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '删除', 'xypm/activity/activity/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '批量更新', 'xypm/activity/activity/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '回收站', 'xypm/activity/activity/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '还原', 'xypm/activity/activity/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @activity_id, @activity_level, '真实删除', 'xypm/activity/activity/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 物业服务的子菜单: 文章管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @service_id, @service_level, '文章管理', 'xypm/article/index', 'IconMenu', 'app/xypm/article/index', 'xypm/article/index', NULL, 2, 1, 'M', 16, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @article_id := LAST_INSERT_ID();
    SET @article_level := CONCAT(@service_level, ',', @article_id);

    -- 文章管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @article_id, @article_level, '选择', 'xypm/article/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @article_id, @article_level, '添加', 'xypm/article/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @article_id, @article_level, '编辑', 'xypm/article/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @article_id, @article_level, '删除', 'xypm/article/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @article_id, @article_level, '批量更新', 'xypm/article/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 费用账单
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '费用账单', 'xypm/bill/bill', 'IconMenu', 'xypm/bill/bill/index', 'xypm/bill/bill/index', NULL, 2, 1, 'M', 16, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @bill_id := LAST_INSERT_ID();
    SET @bill_level := CONCAT(@root_level, ',', @bill_id);

    -- 费用账单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @bill_id, @bill_level, '生成账单', 'xypm/bill/bill/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @bill_id, @bill_level, '删除删除', 'xypm/bill/bill/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 费用账单的子菜单: 房屋账单
    INSERT INTO `sa_system_menu` VALUES (NULL, @bill_id, @bill_level, '房屋账单', 'xypm/bill/bill/index/type/house', 'IconMenu', 'app/xypm/bill/bill/index/type/house/index', 'xypm/bill/bill/index/type/house/index', NULL, 2, 1, 'M', 20, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @house_bill_id := LAST_INSERT_ID();
    SET @house_bill_level := CONCAT(@bill_level, ',', @house_bill_id);

    -- 房屋账单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_bill_id, @house_bill_level, '生成账单', 'xypm/bill/bill/add/type/house', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @house_bill_id, @house_bill_level, '删除', 'xypm/bill/bill/destroy/type/house', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 费用账单的子菜单: 商铺账单
    INSERT INTO `sa_system_menu` VALUES (NULL, @bill_id, @bill_level, '商铺账单', 'xypm/bill/bill/index/type/shop', 'IconMenu', 'app/xypm/bill/bill/index/type/shop/index', 'xypm/bill/bill/index/type/shop/index', NULL, 2, 1, 'M', 19, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @shop_bill_id := LAST_INSERT_ID();
    SET @shop_bill_level := CONCAT(@bill_level, ',', @shop_bill_id);

    -- 商铺账单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_bill_id, @shop_bill_level, '生成账单', 'xypm/bill/bill/add/type/shop', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_bill_id, @shop_bill_level, '删除', 'xypm/bill/bill/destroy/type/shop', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 费用账单的子菜单: 车位账单
    INSERT INTO `sa_system_menu` VALUES (NULL, @bill_id, @bill_level, '车位账单', 'xypm/bill/bill/index/type/parking', 'IconMenu', 'app/xypm/bill/bill/index/type/parking/index', 'xypm/bill/bill/index/type/parking/index', NULL, 2, 1, 'M', 18, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @parking_bill_id := LAST_INSERT_ID();
    SET @parking_bill_level := CONCAT(@bill_level, ',', @parking_bill_id);

    -- 车位账单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_bill_id, @parking_bill_level, '生成账单', 'xypm/bill/bill/add/type/parking', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @parking_bill_id, @parking_bill_level, '删除', 'xypm/bill/bill/destroy/type/parking', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 费用账单的子菜单: 储物间账单
    INSERT INTO `sa_system_menu` VALUES (NULL, @bill_id, @bill_level, '储物间账单', 'xypm/bill/bill/index/type/garage', 'IconMenu', 'app/xypm/bill/bill/index/type/garage/index', 'xypm/bill/bill/index/type/garage/index', NULL, 2, 1, 'M', 17, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @garage_bill_id := LAST_INSERT_ID();
    SET @garage_bill_level := CONCAT(@bill_level, ',', @garage_bill_id);

    -- 储物间账单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_bill_id, @garage_bill_level, '生成账单', 'xypm/bill/bill/add/type/garage', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @garage_bill_id, @garage_bill_level, '删除', 'xypm/bill/bill/destroy/type/garage', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 商城管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '商城管理', 'xypm/shop', 'IconMenu', 'app/xypm/shop', '', NULL, 2, 1, 'M', 15, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @shop_management_id := LAST_INSERT_ID();
    SET @shop_management_level := CONCAT(@root_level, ',', @shop_management_id);

    -- 商城管理的子菜单: 商品分类
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_management_id, @shop_management_level, '商品分类', 'xypm/shop/category', 'IconMenu', 'app/xypm/shop/category/index', 'xypm/shop/category/index', NULL, 2, 1, 'M', 20, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @category_id := LAST_INSERT_ID();
    SET @category_level := CONCAT(@shop_management_level, ',', @category_id);

    -- 商品分类的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @category_id, @category_level, '查看', 'xypm/shop/category/index', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'index', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @category_id, @category_level, '添加', 'xypm/shop/category/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @category_id, @category_level, '编辑', 'xypm/shop/category/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @category_id, @category_level, '删除', 'xypm/shop/category/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @category_id, @category_level, '批量更新', 'xypm//shop/category/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 商城管理的子菜单: 商品列表
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_management_id, @shop_management_level, '商品列表', 'xypm/shop/goods/index', 'IconMenu', 'app/xypm/shop/goods/index', 'xypm/shop/goods/index', NULL, 2, 1, 'M', 19, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @goods_list_id := LAST_INSERT_ID();
    SET @goods_list_level := CONCAT(@shop_management_level, ',', @goods_list_id);

    -- 商品列表的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '选择', 'xypm/shop/goods/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '添加', 'xypm/shop/goods/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '编辑', 'xypm/shop/goods/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '删除', 'xypm/shop/goods/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '批量更新', 'xypm/shop/goods/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '回收站', 'xypm/shop/goods/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '还原', 'xypm/shop/goods/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @goods_list_id, @goods_list_level, '真实删除', 'xypm/shop/goods/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 商城管理的子菜单: 订单管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @shop_management_id, @shop_management_level, '订单管理', 'xypm/order/order/index', 'IconMenu', 'app/xypm/order/order/index', 'xypm/goods/order/index', NULL, 2, 1, 'M', 18, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @order_management_id := LAST_INSERT_ID();
    SET @order_management_level := CONCAT(@shop_management_level, ',', @order_management_id);

    -- 订单管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @order_management_id, @order_management_level, '详情', 'xypm/shop/order/detail', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'detail', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @order_management_id, @order_management_level, '确认收货', 'xypm/shop/order/take_delivery', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'take_delivery', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @order_management_id, @order_management_level, '配送', 'xypm/goods/shop/destroyivery', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'delivery', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 会员管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '会员管理', 'xypm/user', 'IconMenu', 'app/xypm/user', '', NULL, 2, 1, 'M', 14, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @user_management_id := LAST_INSERT_ID();
    SET @user_management_level := CONCAT(@root_level, ',', @user_management_id);

    -- 会员管理的子菜单: 会员列表
    INSERT INTO `sa_system_menu` VALUES (NULL, @user_management_id, @user_management_level, '会员列表', 'xypm/user/user', 'IconMenu', 'xypm/user/user/index', 'xypm/user/user/index', NULL, 2, 1, 'M', 100, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @user_list_id := LAST_INSERT_ID();
    SET @user_list_level := CONCAT(@user_management_level, ',', @user_list_id);

    -- 会员列表的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @user_list_id, @user_list_level, '查看', 'xypm/user/user/index', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'index', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @user_list_id, @user_list_level, '充值', 'xypm/user/user/recharge', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recharge', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @user_list_id, @user_list_level, '选择', 'xypm/user/user/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @user_list_id, @user_list_level, '余额明细', 'xypm/user/money/index', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'money_index', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @user_list_id, @user_list_level, '余额明细删除', 'xypm/user/money/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'money_del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 页面模板
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '页面模板', 'xypm/page', 'IconMenu', 'xypm/page', '', NULL, 2, 1, 'M', 13, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @page_template_id := LAST_INSERT_ID();
    SET @page_template_level := CONCAT(@root_level, ',', @page_template_id);

    -- 页面模板的子菜单: 模板管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @page_template_id, @page_template_level, '模板管理', 'xypm/page/index', NULL, 'xypm/page/index', 'xypm/page/index', NULL, 2, 1, 'M', 100, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @template_management_id := LAST_INSERT_ID();
    SET @template_management_level := CONCAT(@page_template_level, ',', @template_management_id);

    -- 模板管理的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '新建模板', 'xypm/page/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '装修', 'xypm/page/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '历史记录', 'xypm/page/history', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'history', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '发布', 'xypm/page/use', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'use', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '删除', 'xypm/page/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '回收站', 'xypm/page/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '还原', 'xypm/page/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '恢复', 'xypm/page/recover', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recover', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @template_management_id, @template_management_level, '真实删除', 'xypm/page/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 页面模板的子菜单: 页面链接
    INSERT INTO `sa_system_menu` VALUES (NULL, @page_template_id, @page_template_level, '页面链接', 'xypm/link/index', NULL, 'app/xypm/link/index', 'xypm/link/index', NULL, 2, 1, 'M', 99, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @link_management_id := LAST_INSERT_ID();
    SET @link_management_level := CONCAT(@page_template_level, ',', @link_management_id);

    -- 页面链接的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @link_management_id, @link_management_level, '选择', 'xypm/link/select', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'select', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @link_management_id, @link_management_level, '生成', 'xypm/link/load', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'load', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @link_management_id, @link_management_level, '添加', 'xypm/link/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @link_management_id, @link_management_level, '编辑', 'xypm/link/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @link_management_id, @link_management_level, '删除', 'xypm/link/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 页面模板的子菜单: 底部导航
    INSERT INTO `sa_system_menu` VALUES (NULL, @page_template_id, @page_template_level, '底部导航', 'xypm/tabbar/index', 'IconMenu', 'app/xypm/tabbar/index', 'xypm/tabbar/index', NULL, 2, 1, 'M', 98, NULL, 1, 1, '', 1, 1, now(), now(), NULL);

    -- 一级菜单: 财务管理
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '财务管理', 'xypm/money', 'IconMenu', 'app/xypm/money', '', NULL, 2, 1, 'M', 12, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @financial_management_id := LAST_INSERT_ID();
    SET @financial_management_level := CONCAT(@root_level, ',', @financial_management_id);

    -- 财务管理的子菜单: 交费订单
    INSERT INTO `sa_system_menu` VALUES (NULL, @financial_management_id, @financial_management_level, '交费订单', 'xypm/bill/order/index', 'IconMenu', 'app/xypm/bill/order/index', 'xypm/bill/order/index', NULL, 2, 1, 'M', 100, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @payment_order_id := LAST_INSERT_ID();
    SET @payment_order_level := CONCAT(@financial_management_level, ',', @payment_order_id);

    -- 交费订单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @payment_order_id, @payment_order_level, '删除', 'xypm/bill/order/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @payment_order_id, @payment_order_level, '回收站', 'xypm/bill/order/recyclebin', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'recyclebin', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @payment_order_id, @payment_order_level, '还原', 'xypm/bill/order/restore', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'restore', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @payment_order_id, @payment_order_level, '真实删除', 'xypm/bill/order/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'destroy', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 财务管理的子菜单: 充值订单
    INSERT INTO `sa_system_menu` VALUES (NULL, @financial_management_id, @financial_management_level, '充值订单', 'xypm/recharge/order/index', 'IconMenu', 'app/xypm/recharge/order/index', 'xypm/recharge/order/index', NULL, 2, 1, 'M', 100, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @recharge_order_id := LAST_INSERT_ID();
    SET @recharge_order_level := CONCAT(@financial_management_level, ',', @recharge_order_id);

    -- 充值订单的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @recharge_order_id, @recharge_order_level, '删除', 'xypm/recharge/order/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 财务管理的子菜单: 充值套餐
    INSERT INTO `sa_system_menu` VALUES (NULL, @financial_management_id, @financial_management_level, '充值套餐', 'xypm/recharge/recharge', 'IconMenu', 'app/xypm/recharge/recharge/index', 'xypm/recharge/recharge/index', NULL, 2, 1, 'M', 100, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @recharge_package_id := LAST_INSERT_ID();
    SET @recharge_package_level := CONCAT(@financial_management_level, ',', @recharge_package_id);

    -- 充值套餐的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @recharge_package_id, @recharge_package_level, '查看', 'xypm/recharge/recharge/index', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'index', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @recharge_package_id, @recharge_package_level, '添加', 'xypm/recharge/recharge/save', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'add', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @recharge_package_id, @recharge_package_level, '编辑', 'xypm/recharge/recharge/edit', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'edit', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @recharge_package_id, @recharge_package_level, '删除', 'xypm/recharge/recharge/destroy', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'del', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @recharge_package_id, @recharge_package_level, '批量更新', 'xypm/recharge/recharge/multi', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'multi', 1, 0, NULL, 1, 1, now(), now(), NULL);

    -- 一级菜单: 配置中心
    INSERT INTO `sa_system_menu` VALUES (NULL, @root_id, @root_level, '配置中心', 'xypm/config', 'IconMenu', 'app/xypm/config/index', 'xypm/config/index', NULL, 2, 1, 'M', 11, NULL, 1, 1, '', 1, 1, now(), now(), NULL);
    SET @config_center_id := LAST_INSERT_ID();
    SET @config_center_level := CONCAT(@root_level, ',', @config_center_id);

    -- 配置中心的操作
    INSERT INTO `sa_system_menu` VALUES (NULL, @config_center_id, @config_center_level, '查看', 'xypm/config/index', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'index', 1, 0, NULL, 1, 1, now(), now(), NULL);
    INSERT INTO `sa_system_menu` VALUES (NULL, @config_center_id, @config_center_level, '编辑配置', 'xypm/config/set', NULL, NULL, NULL, NULL, 1, 0, 'B', 0, 'set', 1, 0, NULL, 1, 1, now(), now(), NULL);


    -------------------------------------------------------------------------------------------------------------------
    -- 添加表
    CREATE TABLE IF NOT EXISTS `xypm_activity` (
                                                             `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
        `image` varchar(255) NOT NULL DEFAULT '' COMMENT '封面图',
        `signup_start_time` bigint(16) NOT NULL COMMENT '报名开始时间',
        `signup_end_time` bigint(16) NOT NULL COMMENT '报名结束时间',
        `start_time` bigint(16) NOT NULL COMMENT '活动开始时间',
        `end_time` bigint(16) NOT NULL COMMENT '活动结束时间',
        `organizer` varchar(255) NOT NULL DEFAULT '' COMMENT '主办单位',
        `number` int(10) NOT NULL DEFAULT '0' COMMENT '活动人数',
        `signup_num` int(10) DEFAULT '0' COMMENT '已报名人数',
        `city` varchar(255) NOT NULL DEFAULT '' COMMENT '省市区',
        `address` varchar(255) NOT NULL DEFAULT '' COMMENT '详细地址',
        `content` text NOT NULL COMMENT '活动内容',
        `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态:normal=正常,hidden=隐藏',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动表';

    CREATE TABLE IF NOT EXISTS `xypm_activity_signup` (
                                                                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `activity_id` int(10) NOT NULL DEFAULT '0' COMMENT '活动',
        `user_id` int(10) NOT NULL COMMENT '会员',
        `realname` varchar(100) NOT NULL COMMENT '姓名',
        `mobile` varchar(100) NOT NULL COMMENT '电话',
        `buildname` varchar(255) NOT NULL DEFAULT '' COMMENT '房产名称',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='活动报名表';

    CREATE TABLE IF NOT EXISTS `xypm_bill` (
                                                         `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) DEFAULT NULL COMMENT '缴费用户',
        `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '缴费成员',
        `bill_order_id` int(10) NOT NULL DEFAULT '0' COMMENT '缴费订单ID',
        `build_id` int(10) NOT NULL DEFAULT '0' COMMENT '对应房产ID',
        `buildname` varchar(255) DEFAULT NULL COMMENT '名称',
        `buildtype` enum('house','shop','parking','garage') NOT NULL DEFAULT 'house' COMMENT '房产类型:house=房屋,shop=商铺,parking=车位,garage=储物间',
        `cycle` varchar(100) NOT NULL DEFAULT '' COMMENT '交费周期',
        `billdate` varchar(100) NOT NULL DEFAULT '' COMMENT '账单日期',
        `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '费用',
        `pay_time` bigint(16) DEFAULT NULL COMMENT '缴费时间',
        `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:1=已交费,0=待缴费',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '生成时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物业费账单表';

    CREATE TABLE IF NOT EXISTS `xypm_bill_order` (
                                                               `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `order_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '订单号',
        `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '交费用户',
        `member_id` int(10) NOT NULL COMMENT '交费成员',
        `user_build_id` int(10) NOT NULL COMMENT '用户房产ID',
        `buildname` varchar(100) NOT NULL DEFAULT '' COMMENT '房产名称',
        `buildtype` enum('house','shop','parking','garage') NOT NULL DEFAULT 'house' COMMENT '房产类型:house=房屋,shop=商铺,parking=车位,garage=储物间',
        `realname` varchar(100) NOT NULL DEFAULT '' COMMENT '成员姓名',
        `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '成员电话',
        `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
        `total_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
        `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
        `transaction_id` varchar(60) DEFAULT NULL COMMENT '交易单号',
        `payment_json` varchar(2500) DEFAULT NULL COMMENT '交易原始数据',
        `pay_type` enum('wechat','balance') NOT NULL DEFAULT 'wechat' COMMENT '支付方式:wechat=微信支付, balance=余额支付',
        `paytime` bigint(16) DEFAULT NULL COMMENT '支付时间',
        `platform` enum('wxMiniProgram','wxOfficialAccount') DEFAULT 'wxMiniProgram' COMMENT '平台:wxMiniProgram=微信小程序,wxOfficialAccount=微信公众号',
        `ext` text COMMENT '附加字段',
        `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '订单状态:-1=已取消,0=待付款,1=已付款',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE KEY `order_sn` (`order_sn`) USING BTREE,
        KEY `user_id` (`user_id`) USING BTREE,
        KEY `status` (`status`) USING BTREE,
        KEY `createtime` (`createtime`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交费订单表';

    CREATE TABLE IF NOT EXISTS `xypm_bill_order_item` (
                                                                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `bill_order_id` int(10) NOT NULL DEFAULT '0' COMMENT '交费订单',
        `bill_id` int(10) NOT NULL DEFAULT '0' COMMENT '账单',
        `cycle` varchar(100) DEFAULT NULL COMMENT '交费周期',
        `billdate` varchar(100) DEFAULT NULL COMMENT '账单日期',
        `money` decimal(10,2) NOT NULL COMMENT '费用',
        `createtime` bigint(16) DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `order_id` (`bill_order_id`) USING BTREE,
        KEY `goods_id` (`bill_id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='交费订单明细';

    CREATE TABLE IF NOT EXISTS `xypm_build` (
                                                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(100) NOT NULL DEFAULT '1' COMMENT '楼宇名称',
        `unitnum` int(10) NOT NULL DEFAULT '1' COMMENT '单元数量',
        `floornum` int(10) NOT NULL DEFAULT '1' COMMENT '楼宇层数',
        `roomnum` int(10) NOT NULL DEFAULT '1' COMMENT '房屋数量',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('high','multilayer','villa') NOT NULL DEFAULT 'high' COMMENT '楼宇类型:high=高层,multilayer=多层,villa=别墅',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='楼宇表';

    CREATE TABLE IF NOT EXISTS `xypm_build_area` (
                                                               `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
        `type` enum('shop','parking') NOT NULL DEFAULT 'shop' COMMENT '类型:shop=商铺,parking=车位',
        `remark` varchar(255) DEFAULT NULL COMMENT '备注',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态:normal=正常,hidden=隐藏',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='区域表';

    CREATE TABLE IF NOT EXISTS `xypm_build_garage` (
                                                                 `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `number` varchar(100) NOT NULL DEFAULT '' COMMENT '储物间编号',
        `buildarea` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建筑面积',
        `ownername` varchar(100) DEFAULT '' COMMENT '业主姓名',
        `billdate` varchar(100) DEFAULT '' COMMENT '开始收费日期',
        `mobile` varchar(20) DEFAULT '' COMMENT '业主电话',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:1=已绑定业主,0=未绑定业主',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='储物间表';

    CREATE TABLE IF NOT EXISTS `xypm_build_house` (
                                                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `build_id` int(10) NOT NULL DEFAULT '1' COMMENT '楼宇',
        `unit` int(10) NOT NULL DEFAULT '1' COMMENT '单元',
        `floor` int(10) NOT NULL DEFAULT '1' COMMENT '楼层',
        `number` varchar(100) NOT NULL DEFAULT '1' COMMENT '房号',
        `ownername` varchar(100) DEFAULT '' COMMENT '业主姓名',
        `mobile` varchar(20) DEFAULT '' COMMENT '业主电话',
        `buildarea` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建筑面积',
        `billdate` varchar(100) NOT NULL DEFAULT '' COMMENT '开始收费日期',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:1=已绑定业主,0=未绑定业主',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='房屋表';

    CREATE TABLE IF NOT EXISTS `xypm_build_parking` (
                                                                  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `build_area_id` int(10) NOT NULL DEFAULT '1' COMMENT '车位区域',
        `number` varchar(100) NOT NULL DEFAULT '' COMMENT '车位编号',
        `ownername` varchar(100) DEFAULT '' COMMENT '业主姓名',
        `mobile` varchar(20) DEFAULT '' COMMENT '业主电话',
        `billdate` varchar(100) NOT NULL DEFAULT '' COMMENT '开始收费日期',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:1=已绑定业主,0=未绑定业主',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='车位表';

    CREATE TABLE IF NOT EXISTS `xypm_build_shop` (
                                                               `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `build_area_id` int(10) NOT NULL DEFAULT '1' COMMENT '商铺区域',
        `number` varchar(100) NOT NULL DEFAULT '' COMMENT '商铺编号',
        `ownername` varchar(100) DEFAULT '' COMMENT '业主姓名',
        `mobile` varchar(20) DEFAULT '' COMMENT '业主电话',
        `buildarea` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建筑面积',
        `billdate` varchar(100) NOT NULL DEFAULT '' COMMENT '开始收费日期',
        `floor` int(10) NOT NULL DEFAULT '1' COMMENT '所在楼层',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:1=已绑定业主,0=未绑定业主',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商铺表';

    CREATE TABLE IF NOT EXISTS `xypm_cart` (
                                                         `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
        `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品',
        `buy_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
        `sku_price_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规格',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='购物车表';

    CREATE TABLE IF NOT EXISTS `xypm_category` (
                                                             `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(30) NOT NULL DEFAULT '' COMMENT '名称',
        `intro` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
        `type` varchar(100) NOT NULL DEFAULT 'goods' COMMENT '类型',
        `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
        `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '父ID',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `pid` (`pid`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类表';

    CREATE TABLE IF NOT EXISTS `xypm_config` (
                                                           `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(30) NOT NULL DEFAULT '' COMMENT '变量名',
        `group` varchar(30) NOT NULL DEFAULT '' COMMENT '分组',
        `title` varchar(100) NOT NULL DEFAULT '' COMMENT '变量标题',
        `tip` varchar(100) NOT NULL DEFAULT '' COMMENT '变量描述',
        `type` varchar(30) NOT NULL DEFAULT '' COMMENT '类型:string,text,int,bool,array,datetime,date,file',
        `value` longtext NOT NULL COMMENT '变量值',
        `content` longtext CHARACTER SET utf8mb4 COMMENT '变量字典数据',
        `rule` varchar(100) NOT NULL DEFAULT '' COMMENT '验证规则',
        `extend` varchar(255) NOT NULL DEFAULT '' COMMENT '扩展属性',
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE KEY `name` (`name`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='系统配置表';

    INSERT INTO `xypm_config` VALUES (1, 'xypm', 'basic', '通用配置', '', 'array', '{\"name\":\"智慧小区物业管理\",\"propertyname\":\"湖南行云网络科技有限公司\",\"logo\":\"/app/xypm/imgs/logo.jpg\",\"useravatar\":\"/app/xypm/imgs/logo.jpg\",\"domain\":\"\",\"agreement\":1,\"privacy\":2}', NULL, '', '');
    INSERT INTO `xypm_config` VALUES (2, 'share', 'basic', '分享配置', '', 'array', '{\"title\":\"智慧小区物业管理\",\"image\":\"/app/xypm/imgs/share.jpg\"}', NULL, '', '');
    INSERT INTO `xypm_config` VALUES (3, 'wxminiprogram', 'platform', '微信小程序', '', 'array', '{\"app_id\":\"\",\"secret\":\"\"}', NULL, '', '');
    INSERT INTO `xypm_config` VALUES (4, 'fees', 'basic', '收费配置', '', 'array', '{\"house\":\"\",\"shop\":\"\",\"parking\":\"\",\"garage\":\"\"}', NULL, '', '');
    INSERT INTO `xypm_config` VALUES (5, 'appstyle', 'other', '样式通用配置', '', 'array', '{\"mainColor\":\"#56a3ff\",\"navBarBgColor\":\"#ffffff\",\"navBarFrontColor\":\"#000000\",\"pageBgColor\":\"#f7f7f7\",\"textMainColor\":\"#333333\",\"textLightColor\":\"#808080\",\"textPriceColor\":\"#ff5335\",\"pageModuleBgColor\":\"#ffffff\"}', NULL, '', '');
    INSERT INTO `xypm_config` VALUES (6, 'tabbar', 'tabbar', '底部导航', '', 'array', '{\"backgroundColor\":\"#FFFFFF\",\"textColor\":\"#333333\",\"textHoverColor\":\"#56a3ff\",\"list\":[{\"title\":\"首页\",\"link\":\"/pages/index\",\"iconPath\":\"/app/xypm/imgs/tabbar/index.png\",\"selectedIconPath\":\"/app/xypm/imgs/tabbar/index-a.png\",\"show\":1},{\"title\":\"商城\",\"link\":\"/pages/shop\",\"iconPath\":\"/app/xypm/imgs/tabbar/shop.png\",\"selectedIconPath\":\"/app/xypm/imgs/tabbar/shop-a.png\",\"show\":1},{\"title\":\"我的\",\"link\":\"/pages/user\",\"iconPath\":\"/app/xypm/imgs/tabbar/user.png\",\"selectedIconPath\":\"/app/xypm/imgs/tabbar/user-a.png\",\"show\":1}]}', NULL, '', '');

    CREATE TABLE IF NOT EXISTS `xypm_goods` (
                                                          `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `title` varchar(100) NOT NULL DEFAULT '' COMMENT '标题',
        `tags` varchar(255) DEFAULT NULL COMMENT '标签',
        `category_ids` varchar(200) NOT NULL DEFAULT '' COMMENT '所属分类',
        `image` varchar(255) NOT NULL DEFAULT '' COMMENT '商品主图',
        `images` varchar(2500) DEFAULT NULL COMMENT '轮播图',
        `content` text COMMENT '详情',
        `price` varchar(20) NOT NULL DEFAULT '' COMMENT '价格',
        `line_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '划线价格',
        `is_sku` enum('0','1') NOT NULL DEFAULT '0' COMMENT '是否多规格',
        `likes` int(10) NOT NULL DEFAULT '0' COMMENT '收藏人数',
        `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
        `sales` int(10) NOT NULL DEFAULT '0' COMMENT '销量',
        `show_sales` int(10) NOT NULL DEFAULT '0' COMMENT '显示销量',
        `delivery_type` varchar(100) DEFAULT NULL COMMENT '发货方式:express=物流快递,storetake=门店自提',
        `delivery_ids` varchar(100) DEFAULT NULL COMMENT '发货模板',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('up','hidden','down') NOT NULL DEFAULT 'up' COMMENT '状态:up=上架,hidden=隐藏商品,down=下架',
        `createtime` bigint(16) DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品表';

    CREATE TABLE IF NOT EXISTS `xypm_goods_order` (
                                                                `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `order_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '订单号',
        `type` varchar(100) NOT NULL DEFAULT 'goods' COMMENT '类型:goods=商城订单',
        `from` varchar(100) NOT NULL DEFAULT 'buynow' COMMENT '来源',
        `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '购买用户',
        `goods_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '商品总价',
        `delivery_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '运费',
        `total_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '商品总件数',
        `total_amount` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单总金额',
        `total_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
        `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
        `transaction_id` varchar(60) DEFAULT NULL COMMENT '交易单号',
        `payment_json` varchar(2500) DEFAULT NULL COMMENT '交易原始数据',
        `pay_type` enum('wechat','balance') NOT NULL DEFAULT 'wechat' COMMENT '支付方式:wechat=微信支付, balance=余额支付',
        `paytime` bigint(16) DEFAULT NULL COMMENT '支付时间',
        `platform` enum('wxMiniProgram','wxOfficialAccount') DEFAULT 'wxMiniProgram' COMMENT '平台:wxMiniProgram=微信小程序,wxOfficialAccount=微信公众号',
        `buildname` varchar(200) NOT NULL DEFAULT '' COMMENT '房产名称',
        `realname` varchar(100) NOT NULL DEFAULT '' COMMENT '联系人',
        `mobile` varchar(20) NOT NULL COMMENT '联系电话',
        `delivery_time` bigint(16) DEFAULT NULL COMMENT '配送时间',
        `take_delivery_time` bigint(16) DEFAULT NULL COMMENT '收货时间',
        `ext` text COMMENT '附加字段',
        `remark` varchar(255) DEFAULT NULL COMMENT '用户备注',
        `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '订单状态:-1=已取消,0=待付款,1=待配送,2=已配送,3=已完成',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE KEY `order_sn` (`order_sn`) USING BTREE,
        KEY `user_id` (`user_id`) USING BTREE,
        KEY `status` (`status`) USING BTREE,
        KEY `createtime` (`createtime`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='商品订单表';

    CREATE TABLE IF NOT EXISTS `xypm_goods_order_item` (
                                                                     `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `goods_order_id` int(10) NOT NULL DEFAULT '0' COMMENT ' 商品订单',
        `goods_id` int(10) NOT NULL DEFAULT '0' COMMENT '商品',
        `goods_sku_price_id` int(10) NOT NULL DEFAULT '0' COMMENT '规格 id',
        `goods_sku_text` varchar(60) DEFAULT NULL COMMENT '规格名',
        `goods_title` varchar(255) DEFAULT NULL COMMENT '商品名称',
        `goods_image` varchar(255) DEFAULT NULL COMMENT '商品图片',
        `goods_price` decimal(10,2) NOT NULL COMMENT '商品价格',
        `buy_num` int(10) NOT NULL DEFAULT '0' COMMENT '购买数量',
        `goods_weight` int(10) NOT NULL DEFAULT '0' COMMENT '商品重量',
        `createtime` bigint(16) DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `order_id` (`goods_order_id`) USING BTREE,
        KEY `goods_id` (`goods_id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='订单商品明细';

    CREATE TABLE IF NOT EXISTS `xypm_goods_sku` (
                                                              `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `name` varchar(50) NOT NULL DEFAULT '' COMMENT '名称',
        `pid` int(10) NOT NULL DEFAULT '0' COMMENT '所属规格',
        `goods_id` int(10) NOT NULL COMMENT '产品',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品规格表';

    CREATE TABLE IF NOT EXISTS `xypm_goods_sku_price` (
                                                                    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `goods_sku_ids` varchar(255) DEFAULT NULL,
        `goods_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属产品',
        `image` varchar(255) DEFAULT NULL COMMENT '缩略图',
        `stock` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '库存',
        `sales` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '已售',
        `sn` varchar(50) DEFAULT NULL COMMENT '货号',
        `price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '价格',
        `goods_sku_text` varchar(255) DEFAULT NULL COMMENT '规格',
        `weight` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '重量',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '排序',
        `status` varchar(10) NOT NULL DEFAULT 'up' COMMENT '状态',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='商品规格表';

    CREATE TABLE IF NOT EXISTS `xypm_link` (
                                                         `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `type` varchar(100) NOT NULL DEFAULT 'basic' COMMENT '链接类型:basic=基础链接,user=会员中心,notice=公告链接,activity=活动链接',
        `name` varchar(50) NOT NULL DEFAULT '' COMMENT '链接名称',
        `url` varchar(100) NOT NULL DEFAULT '' COMMENT '路径',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='链接表';

    CREATE TABLE IF NOT EXISTS `xypm_member` (
                                                           `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '绑定用户',
        `build_id` int(10) NOT NULL DEFAULT '0' COMMENT '对应房产ID',
        `buildname` varchar(255) DEFAULT NULL COMMENT '名称',
        `buildtype` enum('house','shop','parking','garage') NOT NULL DEFAULT 'house' COMMENT '房产类型:house=房屋,shop=商铺,parking=车位,garage=储物间',
        `identity` enum('1','2','3') NOT NULL DEFAULT '1' COMMENT '身份:1=户主,2=家人,3=租户',
        `realname` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
        `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '手机',
        `idcard` varchar(50) DEFAULT '' COMMENT '身份证号',
        `buildarea` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '建筑面积',
        `billdate` varchar(100) NOT NULL DEFAULT '' COMMENT '开始收费日期',
        `mnums` int(10) NOT NULL DEFAULT '0' COMMENT '成员数量',
        `pid` int(10) DEFAULT '0' COMMENT '邀请成员ID',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `remark` varchar(255) DEFAULT '' COMMENT '备注',
        `status` enum('1','0') NOT NULL DEFAULT '0' COMMENT '状态:1=已绑定会员,0=未绑定会员',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='住户表';

    CREATE TABLE IF NOT EXISTS `xypm_notice` (
                                                           `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
        `image` varchar(255) NOT NULL DEFAULT '' COMMENT '缩略图',
        `content` text NOT NULL COMMENT '内容',
        `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态:normal=正常,hidden=隐藏',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`weigh`,`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='公告表';

    CREATE TABLE IF NOT EXISTS `xypm_page` (
                                                         `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `page_token` varchar(16) NOT NULL DEFAULT '' COMMENT '页面Token',
        `name` varchar(100) NOT NULL DEFAULT '自定义页面' COMMENT '页面名称',
        `cover` varchar(256) DEFAULT NULL COMMENT '页面封面',
        `type` varchar(100) NOT NULL DEFAULT 'index' COMMENT '模板类型:index=首页模板',
        `page` longtext COMMENT '页面配置',
        `item` longtext COMMENT '项目',
        `is_use` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否使用',
        `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='自定义装修页面表';

    INSERT INTO `xypm_page` VALUES (1, 'xzdbpmCN5lUXA0Ok', '首页', '/app/xypm/imgs/home-fm.jpg', 'index', '{\"params\":{\"navigationBarTitleText\":\"\\u667a\\u6167\\u5c0f\\u533a\\u7269\\u4e1a\\u7ba1\\u7406\"},\"style\":{\"navigationBarTextStyle\":\"#000000\",\"navigationBarBackgroundColor\":\"#acedfd\",\"pageBackgroundColor\":\"#f7f7f7\",\"pageBackgroundImage\":\"https:\\/\\/zhwy.xyunku.com\\/uploads\\/20230803\\/b7da54b51840b52ba894052e47b1c0ee.png\"}}', '[{\"name\":\"\\u641c\\u7d22\\u7ec4\\u4ef6\",\"type\":\"search\",\"icon\":\"search\"},{\"name\":\"\\u8f6e\\u64ad\\u7ec4\\u4ef6\",\"type\":\"banner\",\"icon\":\"random\",\"params\":{\"autoplay\":\"true\",\"interval\":\"3000\",\"height\":\"285\",\"indicatorDots\":\"true\",\"indicatorColor\":\"#ffffff\",\"indicatorActiveColor\":\"#56a3ff\",\"tbmargin\":\"0\",\"lrmargin\":\"30\",\"borderRadius\":\"10\"},\"data\":[{\"image\":\"\\/app\\/xypm\\/imgs\\/home-b1.jpg\",\"link\":\"contact\"}]},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u516c\\u544a\\u7ec4\\u4ef6\",\"type\":\"notice\",\"icon\":\"bullhorn\",\"params\":{\"bgColor\":\"#edf3fd\",\"lefticon\":\"\\/app\\/xypm\\/imgs\\/notice.jpg\",\"nums\":\"3\",\"borderRadius\":\"10\",\"scroll\":\"tb\"}},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u83dc\\u5355\\u7ec4\\u4ef6\",\"type\":\"menu\",\"icon\":\"list\",\"params\":{\"title\":\"\",\"linktitle\":\"\",\"link\":\"\",\"colnum\":\"4\",\"borderRadius\":\"10\",\"bgColor\":\"#ffffff\",\"textColor\":\"#333333\"},\"data\":[{\"name\":\"\\u7269\\u4e1a\\u7f34\\u8d39\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav1.png\",\"link\":\"\\/pages\\/user\\/bill\\/list\"},{\"name\":\"\\u6211\\u7684\\u623f\\u4ea7\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav2.png\",\"link\":\"\\/pages\\/user\\/build\\/list\"},{\"name\":\"\\u901a\\u77e5\\u516c\\u544a\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav3.png\",\"link\":\"\\/pages\\/service\\/notice\\/list\"},{\"name\":\"\\u6295\\u8bc9\\u5efa\\u8bae\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav4.png\",\"link\":\"\\/pages\\/service\\/suggest\\/add\"},{\"name\":\"\\u7ef4\\u4fee\\u7533\\u62a5\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav5.png\",\"link\":\"\\/pages\\/service\\/repair\\/add\"},{\"name\":\"\\u5c0f\\u533a\\u6d3b\\u52a8\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav6.png\",\"link\":\"\\/pages\\/service\\/activity\\/list\"},{\"name\":\"\\u5bb6\\u653f\\u670d\\u52a1\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav7.png\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=2&name=\\u5bb6\\u653f\\u670d\\u52a1\"},{\"name\":\"\\u4f18\\u60e0\\u597d\\u7269\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav8.png\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=1&name=\\u4f18\\u60e0\\u597d\\u7269\"}]},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u6807\\u9898\\u7ec4\\u4ef6\",\"type\":\"title\",\"icon\":\"header\",\"params\":{\"title\":\"\\u6700\\u65b0\\u6d3b\\u52a8\",\"linktitle\":\"\\u66f4\\u591a\",\"link\":\"\\/pages\\/service\\/activity\\/list\"}},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"20\"}},{\"name\":\"\\u6d3b\\u52a8\\u7ec4\\u4ef6\",\"type\":\"activity\",\"icon\":\"arrows-alt\",\"params\":{\"bgColor\":\"#ffffff\",\"nums\":\"3\",\"borderRadius\":\"10\"}}]', 1, 'normal', 1694425313, 1694425313, NULL);
    INSERT INTO `xypm_page` VALUES (2, 'Jo0OKR1Yib8LBrHX', '商城', '/app/xypm/imgs/shop-fm.jpg', 'shop', '{\"params\":{\"navigationBarTitleText\":\"\\u5546\\u57ce\"},\"style\":{\"navigationBarTextStyle\":\"#000000\",\"navigationBarBackgroundColor\":\"#dffac2\",\"pageBackgroundColor\":\"#f7f7f7\",\"pageBackgroundImage\":\"https:\\/\\/zhwy.xyunku.com\\/uploads\\/20230803\\/f11b88d6ce9d2acb967a555a7aab6cf1.png\"}}', '[{\"name\":\"\\u641c\\u7d22\\u7ec4\\u4ef6\",\"type\":\"search\",\"icon\":\"search\"},{\"name\":\"\\u8f6e\\u64ad\\u7ec4\\u4ef6\",\"type\":\"banner\",\"icon\":\"random\",\"params\":{\"autoplay\":\"true\",\"interval\":\"3000\",\"height\":\"294\",\"indicatorDots\":\"true\",\"indicatorColor\":\"#333333\",\"indicatorActiveColor\":\"#00dd83\",\"tbmargin\":\"0\",\"lrmargin\":\"30\",\"borderRadius\":\"10\"},\"data\":[{\"image\":\"\\/app\\/xypm\\/imgs\\/shop-b1.jpg\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=2&name=\\u5bb6\\u653f\\u670d\\u52a1\"}]},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u83dc\\u5355\\u7ec4\\u4ef6\",\"type\":\"menu\",\"icon\":\"list\",\"params\":{\"title\":\"\",\"linktitle\":\"\",\"link\":\"\",\"colnum\":\"4\",\"borderRadius\":\"10\",\"bgColor\":\"#ffffff\",\"textColor\":\"#333333\"},\"data\":[{\"name\":\"\\u5168\\u90e8\\u5206\\u7c7b\",\"image\":\"\\/app\\/xypm\\/imgs\\/shop-nav1.png\",\"link\":\"\\/pages\\/shop\\/category\"},{\"name\":\"\\u5bb6\\u653f\\u670d\\u52a1\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav1.png\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=2&name=\\u5bb6\\u653f\\u670d\\u52a1\"},{\"name\":\"\\u4f18\\u60e0\\u597d\\u7269\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav8.png\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=1&name=\\u4f18\\u60e0\\u597d\\u7269\"},{\"name\":\"\\u6211\\u7684\\u8ba2\\u5355\",\"image\":\"\\/app\\/xypm\\/imgs\\/shop-nav2.png\",\"link\":\"\\/pages\\/user\\/shop\\/order\"}]},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u6807\\u9898\\u7ec4\\u4ef6\",\"type\":\"title\",\"icon\":\"header\",\"params\":{\"title\":\"\\u4f18\\u60e0\\u670d\\u52a1\",\"linktitle\":\"\\u66f4\\u591a\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=2&name=\\u5bb6\\u653f\\u670d\\u52a1\"}},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u5546\\u54c1\\u7ec4\\u4ef6\",\"type\":\"goods\",\"icon\":\"archive\",\"data\":[{\"goods_id\":\"5\"},{\"goods_id\":\"6\"},{\"goods_id\":\"7\"},{\"goods_id\":\"8\"}]},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u6807\\u9898\\u7ec4\\u4ef6\",\"type\":\"title\",\"icon\":\"header\",\"params\":{\"title\":\"\\u597d\\u7269\\u63a8\\u8350\",\"linktitle\":\"\\u66f4\\u591a\",\"link\":\"\\/pages\\/shop\\/goods\\/list?cid=1&name=\\u4f18\\u60e0\\u597d\\u7269\"}},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u5546\\u54c1\\u7ec4\\u4ef6\",\"type\":\"goods\",\"icon\":\"archive\",\"data\":[{\"goods_id\":\"1\"},{\"goods_id\":\"2\"},{\"goods_id\":\"3\"},{\"goods_id\":\"4\"}]}]', 1, 'normal', 1694425444, 1694425444, NULL);
    INSERT INTO `xypm_page` VALUES (3, 'ayJX9PwcpEorx6V2', '我的', '/app/xypm/imgs/user-fm.jpg', 'user', '{\"params\":{\"navigationBarTitleText\":\"\\u4e2a\\u4eba\\u4e2d\\u5fc3\"},\"style\":{\"navigationBarTextStyle\":\"#ffffff\",\"navigationBarBackgroundColor\":\"#baf1fd\",\"pageBackgroundColor\":\"#f7f7f7\",\"pageBackgroundImage\":\"\\/app\\/xypm\\/imgs\\/user-page-bg.png\"}}', '[{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u7528\\u6237\\u5361\\u7247\",\"type\":\"user-card\",\"icon\":\"user\"},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u94b1\\u5305\\u6a21\\u5757\",\"type\":\"user-money\",\"icon\":\"jpy\",\"params\":{\"bgColor\":\"#ffffff\",\"borderRadius\":\"10\",\"rechargeicon\":\"\\/app\\/xypm\\/imgs\\/recharge.png\",\"walleticon\":\"\\/app\\/xypm\\/imgs\\/wallet.png\"}},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u83dc\\u5355\\u7ec4\\u4ef6\",\"type\":\"menu\",\"icon\":\"list\",\"params\":{\"title\":\"\\u6211\\u7684\\u670d\\u52a1\",\"linktitle\":\"\",\"link\":\"\",\"colnum\":\"4\",\"borderRadius\":\"10\",\"bgColor\":\"#ffffff\",\"textColor\":\"#333333\"},\"data\":[{\"name\":\"\\u6211\\u7684\\u623f\\u4ea7\",\"image\":\"\\/app\\/xypm\\/imgs\\/user-nav1.png\",\"link\":\"\\/pages\\/user\\/build\\/list\"},{\"name\":\"\\u623f\\u4ea7\\u8d26\\u5355\",\"image\":\"\\/app\\/xypm\\/imgs\\/user-nav2.png\",\"link\":\"\\/pages\\/user\\/bill\\/list\"},{\"name\":\"\\u6211\\u7684\\u62a5\\u4fee\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav5.png\",\"link\":\"\\/pages\\/user\\/service\\/repair\"},{\"name\":\"\\u6211\\u7684\\u5efa\\u8bae\",\"image\":\"\\/app\\/xypm\\/imgs\\/user-nav4.png\",\"link\":\"\\/pages\\/user\\/service\\/suggest\"},{\"name\":\"\\u6211\\u7684\\u6d3b\\u52a8\",\"image\":\"\\/app\\/xypm\\/imgs\\/user-nav5.png\",\"link\":\"\\/pages\\/user\\/service\\/activity\"},{\"name\":\"\\u6211\\u7684\\u6536\\u85cf\",\"image\":\"\\/app\\/xypm\\/imgs\\/home-nav8.png\",\"link\":\"\\/pages\\/user\\/favorite\"},{\"name\":\"\\u8054\\u7cfb\\u5ba2\\u670d\",\"image\":\"\\/app\\/xypm\\/imgs\\/user-nav7.png\",\"link\":\"contact\"},{\"name\":\"\\u4e2a\\u4eba\\u8d44\\u6599\",\"image\":\"\\/app\\/xypm\\/imgs\\/user-nav8.png\",\"link\":\"\\/pages\\/user\\/info\"}]},{\"name\":\"\\u7a7a\\u767d\\u884c\",\"type\":\"empty\",\"icon\":\"window-maximize\",\"params\":{\"height\":\"30\"}},{\"name\":\"\\u83dc\\u5355\\u7ec4\\u4ef6\",\"type\":\"menu\",\"icon\":\"list\",\"params\":{\"title\":\"\\u5546\\u57ce\\u8ba2\\u5355\",\"linktitle\":\"\\u5168\\u90e8\\u8ba2\\u5355\",\"link\":\"\\/pages\\/user\\/shop\\/order\",\"colnum\":\"5\",\"borderRadius\":\"10\",\"bgColor\":\"#ffffff\",\"textColor\":\"#333333\"},\"data\":[{\"name\":\"\\u5f85\\u4ed8\\u6b3e\",\"image\":\"\\/app\\/xypm\\/imgs\\/order-nav1.png\",\"link\":\"\\/pages\\/user\\/shop\\/order?status=1\"},{\"name\":\"\\u5f85\\u914d\\u9001\",\"image\":\"\\/app\\/xypm\\/imgs\\/order-nav2.png\",\"link\":\"\\/pages\\/user\\/shop\\/order?status=2\"},{\"name\":\"\\u5df2\\u914d\\u9001\",\"image\":\"\\/app\\/xypm\\/imgs\\/order-nav3.png\",\"link\":\"\\/pages\\/user\\/shop\\/order?status=3\"},{\"name\":\"\\u5df2\\u5b8c\\u6210\",\"image\":\"\\/app\\/xypm\\/imgs\\/order-nav4.png\",\"link\":\"\\/pages\\/user\\/shop\\/order?status=4\"},{\"name\":\"\\u5df2\\u53d6\\u6d88\",\"image\":\"\\/app\\/xypm\\/imgs\\/order-nav5.png\",\"link\":\"\\/pages\\/user\\/shop\\/order?status=5\"}]}]', 1, 'normal', 1712210536, 1712210536, NULL);


    CREATE TABLE IF NOT EXISTS `xypm_repair` (
                                                           `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) NOT NULL COMMENT '用户',
        `buildname` varchar(255) NOT NULL DEFAULT '' COMMENT '房产',
        `realname` varchar(100) NOT NULL DEFAULT '' COMMENT '姓名',
        `mobile` varchar(100) NOT NULL DEFAULT '' COMMENT '电话',
        `content` text NOT NULL COMMENT '内容',
        `images` text COMMENT '图片',
        `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '状态:0=待处理,1=处理中,2=已处理',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='报修表';

    CREATE TABLE IF NOT EXISTS `xypm_suggest` (
                                                            `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) NOT NULL COMMENT '用户',
        `buildname` varchar(255) NOT NULL DEFAULT '' COMMENT '房产',
        `realname` varchar(100) NOT NULL COMMENT '姓名',
        `mobile` varchar(100) NOT NULL COMMENT '电话',
        `content` text NOT NULL COMMENT '内容',
        `images` text COMMENT '图片',
        `status` enum('0','1','2') NOT NULL DEFAULT '0' COMMENT '状态:0=待处理,1=处理中,2=已处理',
        `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE,
        KEY `weigh_id` (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='投诉建议表';

    CREATE TABLE IF NOT EXISTS `xypm_third` (
                                            `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
                                            `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员ID',
                                            `platform` varchar(30) NOT NULL DEFAULT '' COMMENT '平台',
                                            `openid` varchar(100) NOT NULL DEFAULT '' COMMENT 'openid',
                                            `session_key` varchar(255) NOT NULL DEFAULT '' COMMENT 'session_key',
                                            `logintime` bigint(16) unsigned DEFAULT NULL COMMENT '登录时间',
                                            `createtime` bigint(16) unsigned DEFAULT NULL COMMENT '创建时间',
                                            `updatetime` bigint(16) unsigned DEFAULT NULL COMMENT '更新时间',
                                            PRIMARY KEY (`id`) USING BTREE,
                                            KEY `user_id` (`user_id`,`platform`) USING BTREE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='第三方登录表';

    CREATE TABLE IF NOT EXISTS `xypm_user_build` (
                                                               `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户',
        `member_id` int(10) NOT NULL DEFAULT '0' COMMENT '成员ID',
        `type` enum('house','shop','parking','garage') NOT NULL DEFAULT 'house' COMMENT '房产类型:house=房屋,shop=商铺,parking=车位,garage=储物间',
        `build_id` int(10) NOT NULL COMMENT '房产ID',
        `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '默认',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户房产表';

    CREATE TABLE IF NOT EXISTS `xypm_user_favorite` (
                                                                  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
        `type` enum('coach','store','course','goods') NOT NULL DEFAULT 'coach' COMMENT '类型',
        `target_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '目标ID',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        `deletetime` bigint(16) DEFAULT NULL COMMENT '删除时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户收藏';

    CREATE TABLE IF NOT EXISTS `xypm_user_money` (
                                                               `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '会员',
        `type` enum('sys','pay_goods','pay_bill','recharge') NOT NULL DEFAULT 'sys' COMMENT '变更类型',
        `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更费用',
        `before` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更前',
        `after` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '变更后',
        `service_id` int(10) NOT NULL DEFAULT '0' COMMENT '业务ID',
        `remark` varchar(255) DEFAULT NULL COMMENT '备注',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='会员余额明细表';

    CREATE TABLE IF NOT EXISTS `xypm_user_view` (
                                                              `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `user_id` int(10) NOT NULL DEFAULT '0' COMMENT '用户',
        `type` enum('notice','activity','goods') NOT NULL DEFAULT 'notice' COMMENT '类型',
        `target_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '目标ID',
        `createtime` bigint(16) DEFAULT NULL COMMENT '添加时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户浏览记录';

-- 1.0.1 升级start
    ALTER TABLE `xypm_bill_order` MODIFY COLUMN platform enum('wxMiniProgram','wxOfficialAccount') DEFAULT 'wxMiniProgram' COMMENT '平台:wxMiniProgram=微信小程序,wxOfficialAccount=微信公众号';
    ALTER TABLE `xypm_goods_order` MODIFY COLUMN platform enum('wxMiniProgram','wxOfficialAccount') DEFAULT 'wxMiniProgram' COMMENT '平台:wxMiniProgram=微信小程序,wxOfficialAccount=微信公众号';
    -- 1.0.1 升级end

-- 1.0.2 升级start
    ALTER TABLE `xypm_user_money` MODIFY COLUMN type enum('sys','pay_goods','pay_bill','recharge') DEFAULT 'sys' COMMENT '变更类型';

    CREATE TABLE IF NOT EXISTS `xypm_recharge` (
                                                             `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `facevalue` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '面值',
        `buyprice` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '售价',
        `remark` text COMMENT '套餐说明',
        `status` varchar(30) NOT NULL DEFAULT 'normal' COMMENT '状态:normal=正常,hidden=隐藏',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值套餐表';

    CREATE TABLE IF NOT EXISTS `xypm_recharge_order` (
                                                                   `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `order_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '订单号',
        `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户',
        `money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '充值金额',
        `total_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '支付金额',
        `pay_fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '实际支付金额',
        `transaction_id` varchar(60) DEFAULT NULL COMMENT '交易单号',
        `payment_json` varchar(2500) DEFAULT NULL COMMENT '交易原始数据',
        `pay_type` enum('wechat') DEFAULT 'wechat',
        `paytime` bigint(16) DEFAULT NULL COMMENT '支付时间',
        `ext` text CHARACTER SET utf8mb4 COMMENT '附加字段',
        `platform` enum('wxMiniProgram','wxOfficialAccount') DEFAULT 'wxMiniProgram' COMMENT '平台:wxMiniProgram=微信小程序,wxOfficialAccount=微信公众号',
        `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '订单状态:-2=交易关闭,-1=已取消,0=未支付,1=已支付',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE,
        UNIQUE KEY `order_sn` (`order_sn`) USING BTREE,
        KEY `user_id` (`user_id`) USING BTREE,
        KEY `status` (`status`) USING BTREE,
        KEY `createtime` (`createtime`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值订单表';

-- 1.0.4 升级start
    CREATE TABLE IF NOT EXISTS `xypm_article` (
                                                            `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
        `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
        `image` varchar(100) NOT NULL DEFAULT '' COMMENT '图片',
        `content` text NOT NULL COMMENT '内容',
        `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
        `views` int(10) NOT NULL DEFAULT '0' COMMENT '浏览量',
        `status` enum('normal','hidden') NOT NULL DEFAULT 'normal' COMMENT '状态',
        `createtime` bigint(16) DEFAULT NULL COMMENT '创建时间',
        `updatetime` bigint(16) DEFAULT NULL COMMENT '更新时间',
        PRIMARY KEY (`id`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='文章表';
-- 1.0.4 升级end


    CREATE TABLE `xypm_area` (
       `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
       `pid` int(10) DEFAULT NULL COMMENT '父id',
       `shortname` varchar(100) DEFAULT NULL COMMENT '简称',
       `name` varchar(100) DEFAULT NULL COMMENT '名称',
       `mergename` varchar(255) DEFAULT NULL COMMENT '全称',
       `level` tinyint(4) DEFAULT NULL COMMENT '层级:1=省,2=市,3=区/县',
       `pinyin` varchar(100) DEFAULT NULL COMMENT '拼音',
       `code` varchar(100) DEFAULT NULL COMMENT '长途区号',
       `zip` varchar(100) DEFAULT NULL COMMENT '邮编',
       `first` varchar(50) DEFAULT NULL COMMENT '首字母',
       `lng` varchar(100) DEFAULT NULL COMMENT '经度',
       `lat` varchar(100) DEFAULT NULL COMMENT '纬度',
       PRIMARY KEY (`id`),
       KEY `pid` (`pid`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='地区表'