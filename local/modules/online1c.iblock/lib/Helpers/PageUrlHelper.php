<?php
/**
 * Created by OOO 1C-SOFT.
 * User: dremin_s
 * Date: 21.07.2017
 */

namespace Online1c\Iblock\Helpers;

class PageUrlHelper
{
	public static function makeDetailUrl($template = '')
	{
		preg_match_all('/#([A-Za-z0-9_]+)#/', $template, $match);

		return function ($data) use ($template, $match){
			$url = $template;
			foreach ($match[1] as $match) {
				if(array_key_exists($match, $data)){
					$url = str_replace('#'.$match.'#', $data[$match], $url);
				}
			}
			$data['DETAIL_PAGE_URL'] = $url;

			return $data;
		};
	}
}