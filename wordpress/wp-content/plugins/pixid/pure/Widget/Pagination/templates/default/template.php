<? 
$page_links = $data->get('page_links');
if(empty($page_links)) return;
?>
<!-- ПАГИНАЦИЯ -->
				<div class="pagination clearfix">
					<ul>
					<li class="first"><a href="<? echo $page_links[0]['link']; ?>">Первая</a></li>
<? foreach($data->get('page_links') as $page_link): ?>						
<li class="<? if($page_link['current']): ?>active<? endif; ?>"><a href="<? echo $page_link['link']; ?>"><? echo $page_link['page']; ?></a></li>
<? endforeach; ?>	
<li class="last"><a href="<? echo $page_links[count($page_links)-1]['link']; ?>">Последняя</a></li>
					</ul>
				</div>
