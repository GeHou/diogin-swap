<?php
/**
 * 分片式关系数据库抽象
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

// [内部][实体] 分片式关系数据库
class sharded_rdb extends rdb {}
// [内部][实体] 分片式关系数据库节点池
class sharded_rdb_node_pool extends rdb_node_pool {}
