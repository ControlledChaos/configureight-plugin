<?php
/**
 * Loop options fields
 *
 * @package    Configure 8 Options
 * @subpackage Views
 * @since      1.0.0
 */

?>

<?php echo Bootstrap :: formTitle( [ 'title' => $L->g( 'Posts Loop Options' ) ] ); ?>
<fieldset>

	<legend class="screen-reader-text"><?php $L->p( 'Posts Loop' ); ?></legend>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_title"><?php $L->p( 'Loop Title' ); ?></label>
		<div class="col-sm-10">
			<input type="text" id="loop_title" name="loop_title" value="<?php echo $this->getValue( 'loop_title' ); ?>" placeholder="<?php echo ( $this->getValue( 'loop_style' ) === 'news' ? $L->get( 'News' ) : $L->get( 'Blog' ) ); ?>" />
			<small class="form-text text-muted">
				<?php $L->p( 'The title of posts loop pages. Defaults to the style of loop, Blog or News.' ); ?>
				<br />
				<?php $L->p( 'This setting is overridden if a static page is used for the loop (not home page).' ); ?>
			</small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_description"><?php $L->p( 'Loop description' ); ?></label>
		<div class="col-sm-10">
			<input type="text" id="loop_description" name="loop_description" value="<?php echo $this->getValue( 'loop_description' ); ?>" placeholder="<?php echo ( $this->getValue( 'loop_style' ) === 'news' ? $L->get( 'News' ) : $L->get( 'Blog' ) ); ?>" />
			<small class="form-text text-muted">
				<?php $L->p( 'The description of posts loop pages.' ); ?>
				<br />
				<?php $L->p( 'This setting is overridden if a static page is used for the loop (not home page).' ); ?>
			</small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_style"><?php $L->p( 'Loop Type' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_style" name="loop_style">

				<option value="blog" <?php echo ( $this->getValue( 'loop_style' ) === 'blog' ? 'selected' : '' ); ?>><?php $L->p( 'Blog' ); ?></option>

				<option value="news" <?php echo ( $this->getValue( 'loop_style' ) === 'news' ? 'selected' : '' ); ?>><?php $L->p( 'News' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( 'Choose the style of posts in the main loop.' ); ?></small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="content_style"><?php $L->p( 'Content Style' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="content_style" name="content_style">

				<option value="list" <?php echo ( $this->getValue( 'content_style' ) === 'list' ? 'selected' : '' ); ?>><?php $L->p( 'List' ); ?></option>

				<option value="grid" <?php echo ( $this->getValue( 'content_style' ) === 'grid' ? 'selected' : '' ); ?>><?php $L->p( 'Grid' ); ?></option>

				<option value="full" <?php echo ( $this->getValue( 'content_style' ) === 'full' ? 'selected' : '' ); ?>><?php $L->p( 'Full' ); ?></option>
			</select>
			<small class="form-text text-muted">
				<?php $L->p( 'Choose the style of post content in the main loop.' ); ?>
				<br /><?php $L->p( 'See Settings > General > Advanced in the admin menu to set the number pf articles per page.' ); ?>
			</small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="sidebar_in_loop"><?php $L->p( 'Sidebar in Loop' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="sidebar_in_loop" name="sidebar_in_loop">

				<option value="side" <?php echo ( $this->getValue( 'sidebar_in_loop' ) === 'side' ? 'selected' : '' ); ?>><?php $L->p( 'Aside Posts' ); ?></option>

				<option value="bottom" <?php echo ( $this->getValue( 'sidebar_in_loop' ) === 'bottom' ? 'selected' : '' ); ?>><?php $L->p( 'Below Posts' ); ?></option>

				<option value="none" <?php echo ( $this->getValue( 'sidebar_in_loop' ) === 'none' ? 'selected' : '' ); ?>><?php $L->p( 'No Sidebar' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( 'When using a static page for the posts loop, a sidebar template, if used, will override this setting.' ); ?></small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_paged"><?php $L->p( 'Loop Pagination' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_paged" name="loop_paged">

				<option value="numerical" <?php echo ( $this->getValue( 'loop_paged' ) === 'numerical' ? 'selected' : '' ); ?>><?php $L->p( 'Numerical' ); ?></option>

				<option value="prev_next" <?php echo ( $this->getValue( 'loop_paged' ) === 'prev_next' ? 'selected' : '' ); ?>><?php $L->p( 'Previous/Next' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( 'Choose the style of navigation between pages loops.' ); ?></small>
		</div>
	</div>
</fieldset>

<?php echo Bootstrap :: formTitle( [ 'title' => $L->g( 'Loop Details Options' ) ] ); ?>
<fieldset>

	<legend class="screen-reader-text"><?php $L->p( 'Loop Details' ); ?></legend>

	<p class="form-text text-muted"><?php $L->p( 'These options only affect posts in the main loop. Display may vary by loop style when viewing a single page.' ); ?></p>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_byline"><?php $L->p( 'Author Byline' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_byline" name="loop_byline">
				<option value="true" <?php echo ( $this->getValue( 'loop_byline' ) === true ? 'selected' : '' ); ?>><?php $L->p( 'Show' ); ?></option>
				<option value="false" <?php echo ( $this->getValue( 'loop_byline' ) === false ? 'selected' : '' ); ?>><?php $L->p( 'Hide' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( '' ); ?></small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_date"><?php $L->p( 'Post Date' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_date" name="loop_date">
				<option value="true" <?php echo ( $this->getValue( 'loop_date' ) === true ? 'selected' : '' ); ?>><?php $L->p( 'Show' ); ?></option>
				<option value="false" <?php echo ( $this->getValue( 'loop_date' ) === false ? 'selected' : '' ); ?>><?php $L->p( 'Hide' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( '' ); ?></small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_word_count"><?php $L->p( 'Word Count' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_word_count" name="loop_word_count">
				<option value="true" <?php echo ( $this->getValue( 'loop_word_count' ) === true ? 'selected' : '' ); ?>><?php $L->p( 'Show' ); ?></option>
				<option value="false" <?php echo ( $this->getValue( 'loop_word_count' ) === false ? 'selected' : '' ); ?>><?php $L->p( 'Hide' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( '' ); ?></small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_read_time"><?php $L->p( 'Read Time' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_read_time" name="loop_read_time">
				<option value="true" <?php echo ( $this->getValue( 'loop_read_time' ) === true ? 'selected' : '' ); ?>><?php $L->p( 'Show' ); ?></option>
				<option value="false" <?php echo ( $this->getValue( 'loop_read_time' ) === false ? 'selected' : '' ); ?>><?php $L->p( 'Hide' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( '' ); ?></small>
		</div>
	</div>

	<div class="form-field form-group row">
		<label class="form-label col-sm-2 col-form-label" for="loop_icons"><?php $L->p( 'Icons' ); ?></label>
		<div class="col-sm-10">
			<select class="form-select" id="loop_icons" name="loop_icons">
				<option value="true" <?php echo ( $this->getValue( 'loop_icons' ) === true ? 'selected' : '' ); ?>><?php $L->p( 'Show' ); ?></option>
				<option value="false" <?php echo ( $this->getValue( 'loop_icons' ) === false ? 'selected' : '' ); ?>><?php $L->p( 'Hide' ); ?></option>
			</select>
			<small class="form-text text-muted"><?php $L->p( '' ); ?></small>
		</div>
	</div>

</fieldset>
