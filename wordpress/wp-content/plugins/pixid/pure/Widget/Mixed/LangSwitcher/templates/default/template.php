<ul class="nav-other">
<li><a href="#"><? echo $language['title']; ?></a>
								<ul class="nav-sublist">
<? foreach($switcher as $item): if($item['current']) continue; ?>
<li><a href="<? echo $item['url']; ?>"><? echo $item['title']; ?></a></li>
<? endforeach; ?>	
								</ul>
							</li>
						</ul>
