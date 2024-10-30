<?php
/*
Plugin Name: Modern Portfolio
Plugin URI: https://github.com/mostafa272/Modern-Portfolio
Description: The Modern Portfolio is a simple widget to show posts as portfolios based on different filters
Version: 1.0
Author: Mostafa Shahiri<mostafa2134@gmail.com>
Author URI: https://github.com/mostafa272/
*/
/*  Copyright 2009  Mostafa Shahiri(email : mostafa2134@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
//register widget
add_action("widgets_init", function () { register_widget("Modport_ModernPortfolio"); });
class Modport_ModernPortfolio extends WP_Widget
{
    public function __construct() {
        parent::__construct("modport_modern_portfolio", "Modern Portfolio",
            array("description" => "A simple widget to show posts as portfolios based on different filters"));
            add_action( 'wp_enqueue_scripts',array($this,'modport_modern_portfolio_scripts') );
            add_action( 'wp_ajax_modportfolio',array($this, 'modport_ajax_callback') );
            add_action( 'wp_ajax_nopriv_modportfolio',array($this, 'modport_ajax_callback')  );
    }
    public function form($instance) {

    //initial values

        $title=$instance["title"];
        $type=$instance["type"];
        $alltags=(!empty($instance["alltags"]))?$instance["alltags"]:array("0");
        $allcats=(!empty($instance["allcats"]))?$instance["allcats"]:array("0");
        $allauthors=(!empty($instance["allauthors"]))?$instance["allauthors"]:array("0");
        $count=$instance["count"];
        $limit=$instance["limit"];
        $orderby=$instance["orderby"];
        $sort=$instance["sort"];
        $showcats=$instance["showcats"];
        $showauthor=$instance["showauthor"];
        $showdate=$instance["showdate"];
        $showmodified=$instance["showmodified"];
        $showcomment=$instance["showcomment"];
        $showdesc=$instance["showdesc"];
        $showmorebtn=$instance["showmorebtn"];
        $readmore=(!empty($instance["readmore"]))?$instance["readmore"]:'Read More ...';

    //title field for widget
    $titleId = $this->get_field_id("title");
    $titleName = $this->get_field_name("title");
    echo '<p><label for="'.$titleId.'">Title:</label><br>';
    echo '<input id="'.$titleId.'" type="text" name="'.$titleName.'" value="'.$title.'"></p>';
     //select source type
    $typeId = $this->get_field_id("type");
    $typeName = $this->get_field_name("type");
    echo '<label for="'.$typeId.'">Source Type:</label><br>';
    echo '<select id="'.$typeId.'" name="'.$typeName.'">';
    echo '<option value="1" '.selected( '1', $type ).'>Tags</option>';
    echo '<option value="2" '.selected( '2', $type ).'>Categories</option>';
    echo '<option value="3" '.selected( '3', $type ).'>Authors</option>';
    echo '</select>';
   //get tags
    $alltagsId = $this->get_field_id("alltags");
    $alltagsName = $this->get_field_name("alltags");
   $tags = get_tags();
   echo '<p><label for="'.$alltagsId.'">Tags:</label><br>';
  echo '<select id="'.$alltagsId.'" name="'.$alltagsName.'[]" multiple="true">';
  echo '<option value="0" '.selected('true',in_array('0' , $alltags)?'true':'false' ).'>-- Select tags --</option>';
   foreach ($tags as $tag){
   echo '<option value="'.$tag->term_id.'" '.selected( 'true' , in_array($tag->term_id,$alltags)?'true':'false' ).'>'.$tag->name.'</option>';
   }
   echo '</select></p>';

  //get categories
    $allcatsId = $this->get_field_id("allcats");
    $allcatsName = $this->get_field_name("allcats");
   $cats = get_categories();
   echo '<p><label for="'.$allcatsId.'">Categories:</label><br>';
  echo '<select id="'.$allcatsId.'" name="'.$allcatsName.'[]" multiple="true">';
  echo '<option value="0" '.selected('true',in_array('0' , $allcats)?'true':'false' ).'>-- Select categories --</option>';
   foreach ($cats as $cat){
   echo '<option value="'.$cat->term_id.'" '.selected( 'true' , in_array($cat->term_id,$allcats)?'true':'false' ).'>'.$cat->cat_name.'</option>';
   }
   echo '</select></p>';

   //get authors
     $allauthorsId = $this->get_field_id("allauthors");
    $allauthorsName = $this->get_field_name("allauthors");
   $authors = get_users();
   echo '<p><label for="'.$allauthorsId.'">Authors:</label><br>';
  echo '<select id="'.$allauthorsId.'" name="'.$allauthorsName.'[]" multiple="true">';
  echo '<option value="0" '.selected('true',in_array('0' , $allauthors)?'true':'false' ).'>-- Select authors --</option>';
   foreach ($authors as $author){
   echo '<option value="'.$author->ID.'" '.selected( 'true' , in_array($author->ID,$allauthors)?'true':'false' ).'>'.$author->display_name.'['.$author->user_login.']</option>';
   }
   echo '</select></p>';
   //number of posts to fetch from categories
    $countId = $this->get_field_id("count");
    $countName = $this->get_field_name("count");
    echo '<p><label for="'.$countId.'">Count:</label><br>';
    echo '<input id="'.$countId.'" type="number" name="'.$countName.'" value="'.$count.'"></p>';
    //limit description length
    $limitId = $this->get_field_id("limit");
    $limitName = $this->get_field_name("limit");
    echo '<p><label for="'.$limitId.'">Limit Description Length:</label><br>';
    echo '<input id="'.$limitId.'" type="number" name="'.$limitName.'" value="'.$limit.'"></p>';
    //orderby box
    $orderbyId = $this->get_field_id("orderby");
    $orderbyName = $this->get_field_name("orderby");
    echo '<p><label for="'.$orderbyId.'">Order By:</label><br>';
    echo '<select id="'.$orderbyId.'" name="'.$orderbyName.'">';
    echo '<option value="date" '.selected( 'date', $orderby ).'>Created Date</option>';
    echo '<option value="modified" '.selected( 'modified', $orderby ).'>Modified Date</option>';
    echo '<option value="rand" '.selected( 'rand', $orderby ).'>Random</option>';
    echo '<option value="comment_count" '.selected( 'comment_count', $orderby ).'>Comments Count</option>';
    echo '</select></p>';
    //order type
    $sortId = $this->get_field_id("sort");
    $sortName = $this->get_field_name("sort");
    echo '<p><label for="'.$sortId.'">Order:</label><br>';
    echo '<select id="'.$sortId.'" name="'.$sortName.'">';
    echo '<option value="DESC" '.selected( 'DESC', $sort ).'>Descending</option>';
    echo '<option value="ASC" '.selected( 'ASC', $sort ).'>Ascending</option>';
    echo '</select></p>';

    //text for readmore link
    $readmoreId = $this->get_field_id("readmore");
    $readmoreName = $this->get_field_name("readmore");
    echo '<p><label for="'.$readmoreId.'">Read More Text:</label><br>';
    echo '<input id="'.$readmoreId.'" type="text" name="'.$readmoreName.'" value="'.$readmore.'"></p>';
    //an option for showing categories names of the posts
    $showcatsId = $this->get_field_id("showcats");
    $showcatsName = $this->get_field_name("showcats");
    ?><p><input id="<?php echo $showcatsId;?>" type="checkbox" name="<?php echo $showcatsName;?>" value="1" <?php checked( 1, $showcats );?>>Show Categories</p>
   <?php
   //an option for showing authors names of the posts or pages
   $showauthorId = $this->get_field_id("showauthor");
    $showauthorName = $this->get_field_name("showauthor");
    ?><p><input id="<?php echo $showauthorId;?>" type="checkbox" name="<?php echo $showauthorName;?>" value="1" <?php checked( 1, $showauthor );?>>Show Author</p>
   <?php
   //an option for showing published dates of the posts or pages
   $showdateId = $this->get_field_id("showdate");
   $showdateName = $this->get_field_name("showdate");
    ?><p><input id="<?php echo $showdateId;?>" type="checkbox" name="<?php echo $showdateName;?>" value="1" <?php checked( 1, $showdate );?>>Show Published Date</p>
   <?php
   //an option for showing modified dates of the posts or pages
   $showmodifiedId = $this->get_field_id("showmodified");
   $showmodifiedName = $this->get_field_name("showmodified");
    ?><p><input id="<?php echo $showmodifiedId;?>" type="checkbox" name="<?php echo $showmodifiedName;?>" value="1" <?php checked( 1, $showmodified );?>>Show Modified Date</p>
   <?php
   //an option for showing comments count of the posts or pages
   $showcommentId = $this->get_field_id("showcomment");
   $showcommentName = $this->get_field_name("showcomment");
    ?><p><input id="<?php echo $showcommentId;?>" type="checkbox" name="<?php echo $showcommentName;?>" value="1" <?php checked( 1, $showcomment );?>>Show Comments Count</p>
   <?php
      $showdescId = $this->get_field_id("showdesc");
   $showdescName = $this->get_field_name("showdesc");
    ?><p><input id="<?php echo $showdescId;?>" type="checkbox" name="<?php echo $showdescName;?>" value="1" <?php checked( 1, $showdesc );?>>Show Description</p>
    <?php
      $showmorebtnId = $this->get_field_id("showmorebtn");
   $showmorebtnName = $this->get_field_name("showmorebtn");
    ?><p><input id="<?php echo $showmorebtnId;?>" type="checkbox" name="<?php echo $showmorebtnName;?>" value="1" <?php checked( 1, $showmorebtn );?>>Show Read More Button</p>
    <?php
}
//sanitizing widget parameters
public function update($newInstance, $oldInstance) {
    $values = array();
    $values["title"] = sanitize_text_field($newInstance["title"]);
    $values["type"] = $newInstance["type"];
    $values["alltags"] = $newInstance["alltags"];
    $values["allcats"] = $newInstance["allcats"];
    $values["allauthors"] = $newInstance["allauthors"];
    $values["count"] = intval($newInstance["count"]);
    $values["limit"] = intval($newInstance["limit"]);
    $values["orderby"] = $newInstance["orderby"];
    $values["sort"] = $newInstance["sort"];
    $values["showcats"] = $newInstance["showcats"];
    $values["showauthor"] = $newInstance["showauthor"];
    $values["showdate"] = $newInstance["showdate"];
    $values["showmodified"] = $newInstance["showmodified"];
    $values["showcomment"] = $newInstance["showcomment"];
    $values["showdesc"] = $newInstance["showdesc"];
    $values["showmorebtn"] = $newInstance["showmorebtn"];
    $values["readmore"] = sanitize_text_field($newInstance["readmore"]);
    return $values;
}
//adding CSS file and jquery accordion
function modport_modern_portfolio_scripts() {
         wp_register_style( 'modport-modern-portfolio', plugins_url( 'css/modernportfolio.css', __FILE__ ) );
     wp_register_script( 'modport-modern-portfolio', plugins_url( 'js/script.js', __FILE__ ),array('jquery'),'1.0',true );
     wp_register_script( 'modport-portfolio-script', plugins_url( 'js/fetchmore.js', __FILE__ ),array('jquery'),'1.0',true );
     wp_localize_script( 'modport-portfolio-script', 'modport_ajax_url', array( 'ajax_url' => admin_url('admin-ajax.php'),'check_nonce'=>wp_create_nonce('modport-nonce')) );

}

function modport_modern_portfolio_get_article($allcats,$allauthors,$alltags,$params)
{
$output=array();
//removing zero value from  categories,authors,tags IDs array
 if (($key1 = array_search("0", $allcats)) !== false) {
    unset($allcats[$key1]);
    $allcats=array_values($allcats);
   }
  if (($key2 = array_search("0", $allauthors)) !== false) {
    unset($allauthors[$key2]);
    $allauthors=array_values($allauthors);
   }
   if (($key3 = array_search("0", $alltags)) !== false) {
    unset($alltags[$key3]);
    $alltags=array_values($alltags);
   }
   //getting posts or pages based on filters
   $metakey='';
   $data = get_userdata( get_current_user_id() );
    $current_user_caps = $data->allcaps;
    $post_status=($current_user_caps['read_private_posts']==1)?array('publish','private'):array('publish');
 if($params['count']!=0 && (!empty($allcats) || !empty($allauthors) || !empty($alltags)))
 { $output=get_posts(array('numberposts'=>$params['count'],'category__in'=>$allcats,'author__in'=>$allauthors,'tag__in'=>$alltags,'orderby'=>$params['orderby'],'order'=>$params['sort'],'meta_key'=>$metakey,'post_type'=>'post' ,'post_status'=>$post_status));
 }

  if(!empty($output))
  {  //adding extra attributes
    foreach($output as $p)
   { $tmp1=strtotime($p->post_date);
     $p->image = has_post_thumbnail($p->ID)? '<div class="modport_img"><img src="'.esc_url(get_the_post_thumbnail_url($p->ID)).'" title="'.$p->post_title.'" alt="'.$p->post_title.'"></div>':'';
      $content = get_extended($p->post_content);
      $p->intro = $content['main'];
      $p->link = get_permalink($p->ID);
      $p->post_date= date_i18n(get_option('date_format'),strtotime($p->post_date));
      $p->post_modified= date_i18n(get_option('date_format'),strtotime($p->post_modified));
      $p->author_name = get_the_author_meta('display_name',$p->post_author);
 //adding categories links for posts
      $cats=get_the_category($p->ID);
      foreach($cats as $c)
      $p->catlink=(empty($p->catlink))?'<a class="modport_cats" href="'.esc_url(get_category_link($c->term_id)).'">'.$c->cat_name.'</a>':$p->catlink.', <a class="modport_cats" href="'.esc_url(get_category_link($c->term_id)).'">'.$c->cat_name.'</a>';

      if($params['type']=='bytags')
      {
        $tags=get_the_tags($p->ID);
        foreach($tags as $t)
        $p->classname=(empty($p->classname))?'modcolumn class'.$t->term_id:$p->classname.' class'.$t->term_id;
      }
      else if($params['type']=='bycats')
      { foreach($cats as $c)
        $p->classname=(empty($p->classname))?'modcolumn class'.$c->term_id:$p->classname.' class'.$c->term_id;
      }
      else if($params['type']=='byauthors')
      { $p->classname=(empty($p->classname))?'modcolumn class'.$p->post_author:$p->classname.' class'.$p->post_author;
      }

   }
   }
 return $output;
}
function modport_ajax_callback(){
$params=array();
check_ajax_referer( 'modport-nonce', 'security' );
$cur_count= isset($_POST['current_count'])?intval(sanitize_text_field($_POST['current_count'])):0;
$params['type']= isset($_POST['load_type'])?sanitize_text_field($_POST['load_type']):'';
$params['count']= isset($_POST['load_count'])?intval(sanitize_text_field($_POST['load_count'])):0;
$params['orderby']=isset($_POST['load_orderby'])?sanitize_text_field($_POST['load_orderby']):'';
$params['sort']= isset($_POST['load_sort'])?sanitize_text_field($_POST['load_sort']):'';
$allcats= isset($_POST['load_cats'])?explode(',',sanitize_text_field($_POST['load_cats'])):array();
$allauthors= isset($_POST['load_authors'])?explode(',',sanitize_text_field($_POST['load_authors'])):array();
$alltags= isset($_POST['load_tags'])?explode(',',sanitize_text_field($_POST['load_tags'])):array();
$params['count']= $params['count']+$cur_count;
$attribs=explode(',',sanitize_text_field($_POST['load_attribs']));
$category=(!empty($attribs[0]))?intval($attribs[0]):0;
$author=(!empty($attribs[1]))?intval($attribs[1]):0;
$date=(!empty($attribs[2]))?intval($attribs[2]):0;
$modified=(!empty($attribs[3]))?intval($attribs[3]):0;
$comment=(!empty($attribs[4]))?intval($attribs[4]):0;
$readmore=(!empty($attribs[5]))?sanitize_text_field($attribs[5]):'';
$limit=(!empty($attribs[6]))?intval($attribs[6]):18;
$desc= (!empty($attribs[7]))?intval($attribs[7]):0;
$morebtn=(!empty($attribs[8]))?intval($attribs[8]):0;
$posts_info= $this->modport_modern_portfolio_get_article($allcats,$allauthors,$alltags,$params);
if(!empty($posts_info))
{
 foreach($posts_info as $k=>$c)
{
  $c_tmp=($category==1)?'<small class="modportinfo">Category: '.$c->catlink.'</small><br>':'';
  $a_tmp=($author==1)?'<small class="modportinfo">Author: '.$c->author_name.'</small><br>':'';
  $d_tmp=($date==1)?'<small class="modportinfo">Published: '.$c->post_date.'</small><br>':'';
  $m_tmp=($modified==1)?'<small class="modportinfo">Modified: '.$c->post_modified.'</small><br>':'';
  $com_tmp=($comment==1)?'<small class="modportinfo">Comments: '.$c->comment_count.'</small><br>':'';
  $desc_tmp=($desc==1)?'<p>'.wp_trim_words($c->intro,$limit,'...').'</p>':'';
  $morebtn_tmp= ($morebtn==1)?'<a class="modport_readmore" href="'.esc_url($c->link).'">'.esc_html($readmore).'</a>':'';
  $info_block=(!empty($c_tmp.$a_tmp.$d_tmp.$m_tmp.$com_tmp))?'<div class="infoblock">'.$c_tmp.$a_tmp.$d_tmp.$m_tmp.$com_tmp.'</div>':'';
   $img_block = '<div class="modport_overlay"><a class="modport_imglink" href="'.esc_url($c->link).'"></a></div>';
  echo '<div class="'.$c->classname.'">';
    echo '<div class="modport_content"><div class="modport_container">'.$c->image.$img_block.'</div>'.$info_block.'<h4>'.$c->post_title.'</h4>'.$desc_tmp.$morebtn_tmp;
   echo '</div>';
 echo '</div>';

}

}
wp_die();
}
public function widget($args, $instance) {
      wp_enqueue_style( 'modport-modern-portfolio');
     wp_enqueue_script( 'modport-modern-portfolio');
     wp_enqueue_script( 'modport-portfolio-script');
  $title=$instance["title"];
  $type=$instance["type"];
  $alltags=$instance["alltags"];
  $allcats=$instance["allcats"];
  $allauthors=$instance["allauthors"];
  $count=$instance["count"];
  $limit=$instance["limit"];
  $orderby=$instance["orderby"];
  $sort=$instance["sort"];
  $category=$instance["showcats"];
  $author=$instance["showauthor"];
  $date=$instance["showdate"];
  $modified=$instance["showmodified"];
  $comment=$instance["showcomment"];
  $desc=$instance["showdesc"];
  $morebtn=$instance["showmorebtn"];
  $readmore=$instance["readmore"];
  $source=array();
  $sourcename=array();
  if($type=='1')
  { $selectedtype='bytags';
    $source=$alltags;
    for($i=0;$i<count($source);$i++)
    { $sourcetmp[$i]= &get_tag($source[$i]);
      $sourcename[$i]= $sourcetmp[$i]->name;
    }
  }
  else if($type=='2')
  {
  $selectedtype='bycats';
  $source=$allcats;
   for($i=0;$i<count($source);$i++)
    { $sourcename[$i]= get_the_category_by_ID($source[$i]);
    }
  }
  else if($type=='3')
  {
  $selectedtype='byauthors';
  $source=$allauthors;
     for($i=0;$i<count($source);$i++)
    { $sourcename[$i]=  get_the_author_meta('display_name',$source[$i]);

    }
  }
  $attribs=$category.','.$author.','.$date.','.$modified.','.$comment.','.$readmore.','.$limit.','.$desc.','.$morebtn;
  //getting posts by selected filters
  $params=array('type'=>$selectedtype,'count'=>$count,'orderby'=>$orderby,'sort'=>$sort);
  $posts_info= $this->modport_modern_portfolio_get_article($allcats,$allauthors,$alltags,$params);

  //displaying the widget on frontend. It shows the title of widget if it is not empty
  echo $args['before_widget'];
  if(!empty($title))
  {	echo $args['before_title'];
    echo esc_html($title);
  	echo $args['after_title'];
  }
//showing the selected widgets
echo '<div id="modern_portfolio">';
 echo'<div id="modportBtnContainer">';
  echo "<button class=\"btn showall active\" onclick=\"filterSelection('all')\"> Show all</button>";
  for($i=0;$i<count($source);$i++)
  echo "<button class=\"btn\" onclick=\"filterSelection('class".$source[$i]."')\">".$sourcename[$i]."</button>";
 echo '</div>';
echo '<div class="modrow">';
//display posts
echo '<div class="moditems">';
if(!empty($posts_info))
{
 foreach($posts_info as $c)
{

  $c_tmp=($category==1)?'<small class="modportinfo">Category: '.$c->catlink.'</small><br>':'';
  $a_tmp=($author==1)?'<small class="modportinfo">Author: '.$c->author_name.'</small><br>':'';
  $d_tmp=($date==1)?'<small class="modportinfo">Published: '.$c->post_date.'</small><br>':'';
  $m_tmp=($modified==1)?'<small class="modportinfo">Modified: '.$c->post_modified.'</small><br>':'';
  $com_tmp=($comment==1)?'<small class="modportinfo">Comments: '.$c->comment_count.'</small><br>':'';
  $desc_tmp=($desc==1)?'<p>'.wp_trim_words($c->intro,$limit,'...').'</p>':'';
  $morebtn_tmp= ($morebtn==1)?'<a class="modport_readmore" href="'.esc_url($c->link).'">'.esc_html($readmore).'</a>':'';
  $info_block=(!empty($c_tmp.$a_tmp.$d_tmp.$m_tmp.$com_tmp))?'<div class="infoblock">'.$c_tmp.$a_tmp.$d_tmp.$m_tmp.$com_tmp.'</div>':'';
  $img_block = (!empty($c->image))?'<div class="modport_overlay"><a class="modport_imglink" href="'.esc_url($c->link).'"></a></div>':'';
  echo '<div class="'.$c->classname.'">';
    echo '<div class="modport_content"><div class="modport_container">'.$c->image.$img_block.'</div>'.$info_block.'<h4>'.$c->post_title.'</h4>'.$desc_tmp.$morebtn_tmp;
   echo '</div>';
 echo '</div>';
 
}

}
echo '</div>';
echo '<div id="load_container"><div id="modport_loading" ><div id="modport_loader"></div><div id="modport_loadtext"> Loading ...</div></div><button id="modport_load" class="" data-cats="'.implode(',',$allcats).'" data-authors="'.implode(',',$allauthors).'" data-tags="'.implode(',',$alltags).'" data-type="'.$params['type'].'" data-count="'.$params['count'].'" data-orderby="'.$params['orderby'].'" data-sort="'.$params['sort'].'" data-attribs="'.$attribs.'"> Load More</button></div>';
echo '</div>';
echo '</div>';

 echo $args['after_widget'];
}
}