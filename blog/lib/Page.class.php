<?php

/**
 * 翻页类
 */
class Page
{

	/**
	 * @param $page,
	 * @param $pagesize,
	 * @param $total 总记录数
	 * @param $url 请求的URL的地址
	 * @param $url_param URL携带的额外参数
	 */
	public function show($page, $pagesize, $total, $url, $url_param=array())
	{
		// 数据初始设置，url的处理，页码的处理
		// url的形成，由基本url+url额外参数
		$url_info = parse_url($url);
		// 判断是否存在 查询字符串 query元素！
		if (isset($url_info['query'])) {
			// 已经存在url参数，使用&连接上额外参数
			$url .= '&';
		} else {
			// 没有urlc参数，使用?连接上额外参数
			$url .= '?';
		}
		// 连接上额外参数, 将page=空字符串作为额外参数
		$url_param['page'] = '';
		$url .= http_build_query($url_param);

		// 处理页码
		// 尾页：
		$total_page = ceil($total/$pagesize); 

		// 拼凑 第一页	
		// heredoc, 定界符
		$first_html = <<<HTML
		<li>
            <a href='{$url}1' aria-label="First">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>
HTML;
		// 页码翻页部分
		$begin = ($page-2) < 1 ? 1 : ($page-2);
		$end = ($page+2) > $total_page ? $total_page : ($page+2);
		$number_html = '';
		for($i=$begin; $i<=$end; ++$i) {
			// 当前页
			$active = $page==$i ? 'active' : '';
			$number_html .= <<<HTML
			<li class="$active">
	            <a href="$url$i">$i</a>
	        </li>
HTML;
		}

		// 尾页部分
		$end_html = <<<HTML
		<li>
	        <a href="$url$total_page" aria-label="End">
	            <span aria-hidden="true">&raquo;</span>
	        </a>
	    </li>
HTML;

		return '<ul class="pagination">' . $first_html . $number_html . $end_html . '</ul>';// 翻页的HTML代码！
	}

}