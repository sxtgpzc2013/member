<?php if (!defined('THINK_PATH')) exit();//判断是否加载thinkphp,如果否则退出
/*********文件描述*********
 * @last update 2014-06-12
 * @alter  
 * @version 1.0.0
 *
 * 功能简介：商户后台首页MODEL类
 * @author  
 * @copyright  
 * @time 2014-06-12
 * @version 1.0.0 
 */

	class IndexModel extends Model
	{
		/**
		 * 查询单条
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   
		 */
		public function my_find(array $parameter)
		{
			$table = M($parameter['table_name']);

			$result = $table -> where($parameter['where']) -> find();

			return $result;
		}

		/**
		 * 新增数据
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   新增记录ID
		 */
		public function my_add(array $parameter)
		{
			$table = M($parameter['table_name']);

			$result = $table -> data($parameter['data']) -> add();

			return $result;
		}

		/**
		 * 修改方法
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   影响行数
		 */
		public function my_save(array $parameter)
		{
			$table = M($parameter['table_name']);

			$result = $table -> where($parameter['where']) -> save($parameter['data']);

			return $result;
		}

		/**
		 * 简单查询
		 *
		 * 参数描述：
		 *  parameter 	参数
		 *    			
		 *
		 * 返回值：
		 *   结果集
		 */
		public function easy_select(array $parameter)
		{
			$table = M($parameter['table_name']);

			$result = $table -> where($parameter['where']) -> select();

			return $result;
		}

		/**
		 * 多表复杂查询
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   结果集
		 */
		public function table_select(array $parameter)
		{
			//页码
			$p = intval($_GET['p']) ? intval($_GET['p']) : 1;

			$table = M($parameter['table_name']);

			$data['result'] = $table -> table($parameter['tables']) -> where($parameter['where']) -> order($parameter['order']) -> field($parameter['field']) -> page($p.',50') -> select();

			$count = $table -> table($parameter['tables']) -> where($parameter['where']) -> count();

			$Page = new Page($count,50);

			$data['page'] = $Page->show();

			return $data;
		}

		/**
		 * 获取记录条数
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   查询到记录的条数
		 */
		public function get_count(array $parameter)
		{
			$table = M($parameter['table_name']);

			$result = $table -> where($parameter['where']) -> count();

			return $result;
		}

		/**
		 * 排序查询
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   结果集
		 */
		public function order_select(array $parameter, $is_page = 'yes')
		{
			if ($is_page == 'yes')
			{
				//页码
				$p = intval($_GET['p']) ? intval($_GET['p']) : 1;

				$table = M($parameter['table_name']);

				$data['result'] = $table -> where($parameter['where']) -> order($parameter['order']) -> page($p.',10') -> select();

				$count = $table -> where($parameter['where']) -> count();

				$Page = new Page($count, 10);

				$data['page'] = $Page -> show();

				return $data;
			}
			elseif ($is_page == 'no')
			{
				$table = M($parameter['table_name']);

				$data['result'] = $table -> where($parameter['where']) -> order($parameter['order']) -> select();

				return $data;
			}
		}
		/**
		 * 排号数量统计
		 *
		 * 参数描述：
		 *   parameter 	参数
		 *
		 * 返回值：
		 *   结果集
		 */
		public function order_select_count(array $parameter)
		{
				$table = M($parameter['table_name']);
				$data = $table -> where($parameter['where']) -> count();
				//查询五天数据

				//var_dump($table ->getLastSql());
				return $data;
		}
	}