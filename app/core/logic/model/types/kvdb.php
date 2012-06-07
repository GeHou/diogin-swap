<?php
/**
 * 基于键值数据库的领域模型
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 键值模型接口
 */
interface kvdb_model_api extends model_api {}
/**
 * [类型] 键值模型
 */
abstract class kvdb_model extends model implements kvdb_model_api {}
