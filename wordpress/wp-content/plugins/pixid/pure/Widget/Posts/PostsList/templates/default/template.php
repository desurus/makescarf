<ul>
<? foreach($posts as $post): ?>
<li><a href="<? echo get_permalink($post->ID); ?>"><? echo $post->post_title; ?></a></li>
<? endforeach; ?>
</ul>
