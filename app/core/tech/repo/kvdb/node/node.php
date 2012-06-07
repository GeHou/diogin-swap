<?php

namespace swap;

/* type */ abstract class kvdb_node {}
/* type */ class master_kvdb_node extends kvdb_node {}
/* type */ class slave_kvdb_node extends kvdb_node {}

/* entity */ class kvdb_node_pool {}

/* internal entity */ class kvdb_node_util {}
