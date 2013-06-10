<?php if ( $this->app->getControllerName() === 'Reading' || $this->app->getControllerName() === 'Folder' ): ?>
<script>
	readable.itemCount = <?php echo $this->get('itemCount') ?>;
</script>
<?php endif ?>

<?php $items = $this->get('items', false) ?>

<?php if ( !$items ): ?>
<script>
	if ( readable.items ) {
		readable.items.noMoreItems = true;
	} else {
		readable.noMoreItems = true;
	}
</script>

<div id="items-footer">
	<p>
		<i class="entypo chevron-small-left"></i> No more articles <i class="entypo chevron-small-right"></i>
	</p>
</div>

<?php else: ?>

<?php foreach ( $items as $item ): ?>
<article
	data-item-id="<?php echo $item->id ?>"
	data-item-score="<?php echo $item->score ?>"
	data-item-url="<?php echo $this->htmlEncode($item->url) ?>"
	data-item-date="<?php echo date('F j, Y', $item->posted_at) ?>"
	data-feed-host="<?php echo parse_url($item->feed_link, PHP_URL_HOST) ?>"
	class="inactive collapsed <?php echo $item->score < 0 ? ' boring' : '' ?>"
	>
	<h1<?php echo $item->score < 0 ? ' title="You may find this article uninteresting (based on articles you voted on)"' : '' ?>>
		<a href="<?php echo $this->htmlEncode($item->url) ?>"><?php echo $item->title ?></a>
	</h1>

	<p class="item-date">
		<i class="entypo book"></i>
		By
		<strong><a href="<?php echo $this->app->getSingleton('helper')->getFeedLink($item->feed_id, $item->feed_title) ?>" title="<?php echo parse_url($item->feed_link, PHP_URL_HOST) ?>"><?php echo $item->feed_title ?></a></strong>
		<?php echo $item->posted_at ? 'on ' . date('F j, Y', $item->posted_at) : '' ?>
		<?php if ( $this->app->getControllerName() === 'Reading' && $item->folder_title ): ?>
		in <strong><a href="<?php echo $this->app->getSingleton('helper')->getFolderLink($item->folder_id, $item->folder_title) ?>"><?php echo $item->folder_title ?></a></strong>
		<?php endif ?>
		<span class="feed-options">
			&mdash;
			<?php if ( $this->get('controller') == 'index' ): ?>
			<a href="<?php echo $this->app->getRootPath() ?>report/article/<?php echo $item->id ?>" class="report" data-feed-id="<?php echo $item->feed_id ?>" title="Report inappropriate content">
				<i class="entypo flag"></i>Report
			</a>
			&nbsp;
			<?php endif ?>
			<a href="javascript: void(0);" class="subscription <?php echo $item->feed_subscribed ? 'unscubscribe' : 'subscribe' ?>" data-feed-id="<?php echo $item->feed_id ?>" title="Subscriptions appear in &lsquo;My Reading&rsquo;">
				<?php if ( $item->feed_subscribed ): ?>
				<i class="entypo squared-minus"></i>&nbsp;Unsubscribe
				<?php else: ?>
				<i class="entypo squared-plus"></i>&nbsp;Subscribe
				<?php endif ?>
			</a>
		</span>
	</p>

	<div class="item-wrap">
		<div class="item-contents">
			<?php echo $item->contents ?>
		</div>

		<div class="article-buttons">
			<button class="btn btn-small item-vote<?php echo $item->vote == 1 ? ' btn-inverse voted' : '' ?>" data-item-id="<?php echo $item->id ?>" data-vote="1"  title="Promote articles like these in &lsquo;My Reading&rsquo;">
				<i class="entypo thumbs-up"></i>&nbsp;Interesting
			</button>

			<button class="btn btn-small item-vote<?php echo $item->vote == -1 ? ' btn-inverse voted' : '' ?>" data-item-id="<?php echo $item->id ?>" data-vote="-1" title="Demote articles like these in &lsquo;My Reading&rsquo;">
				<i class="entypo thumbs-down"></i>&nbsp;Boring
			</button>

			<button class="btn btn-small item-save<?php echo $item->saved ? ' btn-inverse saved' : '' ?>" data-item-id="<?php echo $item->id ?>" title="Save this article to read later">
				<i class="entypo install"></i>&nbsp;Save<?php echo $item->saved ? 'd' : '' ?>
			</button>

			<div class="share">
				<button class="btn btn-small item-share" title="Share this article">
					<i class="entypo share"></i>&nbsp;Share
				</button>

				<ul>
					<li><a href="http://www.facebook.com/sharer.php?s=100&amp;p[url]=<?php echo rawurlencode($item->url) ?>&amp;p[title]=<?php echo rawurlencode($item->title) ?>">Facebook</a></li>
					<li><a href="https://plus.google.com/share?url=<?php echo rawurlencode($item->url) ?>">Google+</a></li>
					<li><a href="http://twitter.com/home?status=<?php echo rawurlencode($item->title . ' ' . $item->url) ?> via @readablecc">Twitter</a></li>
				</ul>
			</div>
		</div>

		<?php if ( $item->score < 0 ): ?>
		<p class="alert">
			Based on content you voted on this article has automatically been marked as &lsquo;Boring&rsquo;. If we got it wrong, press &lsquo;Interesting&rsquo; to help us understand your interests better.
		</p>
		<?php endif ?>
	</div>
</article>
<?php endforeach ?>
<?php endif ?>

<?php if ( !empty($_GET['page']) && $page = (int) abs($_GET['page']) ): ?>
<p class="pagination">
	<?php if ( $page > 1 ): ?>
	<a href="?page=<?php echo $page - 1 ?>">Previous page</a> &nbsp; &mdash; &nbsp;
	<?php endif ?>

	<a href="?page=<?php echo $page + 1 ?>">Next page</a>
</p>
<?php endif ?>
