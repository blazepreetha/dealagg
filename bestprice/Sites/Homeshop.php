<?php
class Homeshop extends Parsing{
	public $_code = 'Homeshop18';

	public function getAllowedCategory(){
		return array(Category::BOOKS,Category::TV,Category::CAMERA,Category::COMP_ACC,Category::COMP_LAPTOP,Category::HOME_APPLIANCE,Category::MOBILE,Category::TABLETS,Category::BEAUTY);
	}

	public function getWebsiteUrl(){
		return 'http://www.homeshop18.com/';
	}
	public function getLogo(){
		return "http://www.homeshop18.com/homeshop18/media/images/homeshop18_2011/header/hs18-logo.png";
	}
	public function getSearchURL($query,$category = false){
		if($category == Category::BEAUTY){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:3471";
		}else if($category == Category::BOOKS){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:10000";
		}else if($category == Category::CAMERA){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:3159";
		}else if($category == Category::COMP_ACC || $category == Category::COMP_LAPTOP || $category == Category::TABLETS){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:3254";
		}else if($category == Category::HOME_APPLIANCE){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:3575";
		}else if($category == Category::MOBILE){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:3024";
		}else if($category == Category::TV){
			return "http://www.homeshop18.com/$query/search:$query/categoryid:3203";
		}else{
			return "http://www.homeshop18.com/$query/search:$query";
		}
	}
	public function getData($html,$query,$category){
		$data = array();
		phpQuery::newDocumentHTML($html);

		if($category == Category::BOOKS){
			foreach(pq('div.book_rock') as $div){
				if(sizeof(pq($div)->find('.listView_image'))){
					$image = pq($div)->find('.listView_image')->html();
					$url = pq($div)->find('.listView_image')->attr('href');
					$name = pq($div)->find('.listView_details')->find('.listView_title')->find('a')->html();
					$disc_price = pq($div)->find('.listView_details')->find('.listView_price')->find('.our_price')->html();
					$offer = '';
					$shipping = pq($div)->find('.listView_info')->find('.listView_shipping')->html();
					$stock = 0;
					if(sizeof(pq($div)->find('.listView_info')->find('.in_stock')) > 0){
						$stock = 1;
					}else{
						$stock = -1;
					}
					$author = pq($div)->find('.listView_details')->find('.listView_title')->find('span')->html();
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'cat' => Category::BOOKS
					);
				}
			}
		}else{
			foreach(pq('div.product_div') as $div){
				if(sizeof(pq($div)->find('.product_image'))){
					$image = pq($div)->find('.product_image')->find('a')->html();
					$url = pq($div)->find('.product_image')->find('a')->attr('href');
					$name = strip_tags(pq($div)->find('.product_title')->find('a')->html());
					$disc_price = strip_tags(pq($div)->find('.product_price')->find('.product_old_price')->html());
					//$org_price = strip_tags(pq($div)->find('.product_price')->find('.product_new_price')->html());
					$offer = '';
					$shipping = '';
					$stock = 0;
					$author = '';
					$cat = '';
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'cat' => $cat
					);
				}
			}
		}
		$data2 = array();
		foreach($data as $row){
			$html = $row['image'];
			$html .= '</img>';
			phpQuery::newDocumentHTML($html);
			$img = pq('img')->attr('src');
			if(strpos($img, 'http') === false){
				$img = $this->getWebsiteUrl().$img;
			}
			$row['image'] = $img;
			$data2[] = $row;
		}
		$data2 = $this->cleanData($data2, $query);
		$data2 = $this->bestMatchData($data2, $query);
		return $data2;
	}
}