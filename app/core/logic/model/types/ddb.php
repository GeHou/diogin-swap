<?php
/**
 * 基于文档数据库的领域模型
 *
 * @copyright Copyright (c) 2009-2012 Jingcheng Zhang <diogin@gmail.com>. All rights reserved.
 * @license   See "LICENSE" file bundled with this distribution.
 */

namespace swap;

/**
 * [类型] 文档模型接口
 */
interface ddb_model_api extends model_api {}
/**
 * [类型] 文档模型
 */
abstract class ddb_model extends model implements ddb_model_api {}
