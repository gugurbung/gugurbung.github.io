<?php
namespace gugur;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<script type="text/template" id="tmpl-gugur-hotkeys">
	<# var ctrlLabel = environment.mac ? 'Cmd' : 'Ctrl'; #>
	<div id="gugur-hotkeys__content">
		<div id="gugur-hotkeys__actions" class="gugur-hotkeys__col">

			<div class="gugur-hotkeys__header">
				<h3><?php echo __( 'Actions', 'gugur' ); ?></h3>
			</div>
			<div class="gugur-hotkeys__list">
				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Undo', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Z</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Redo', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>Z</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Copy', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>C</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Paste', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>V</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Paste Style', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>V</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Delete', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>Delete</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Duplicate', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>D</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Save', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>S</span>
					</div>
				</div>

			</div>
		</div>

		<div id="gugur-hotkeys__navigation" class="gugur-hotkeys__col">

			<div class="gugur-hotkeys__header">
				<h3><?php echo __( 'Go To', 'gugur' ); ?></h3>
			</div>
			<div class="gugur-hotkeys__list">
				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Finder', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>E</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Show / Hide Panel', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>P</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Responsive Mode', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>M</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'History', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>H</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Navigator', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>I</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Template Library', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>Shift</span>
						<span>L</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Keyboard Shortcuts', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>{{{ ctrlLabel }}}</span>
						<span>?</span>
					</div>
				</div>

				<div class="gugur-hotkeys__item">
					<div class="gugur-hotkeys__item--label"><?php echo __( 'Quit', 'gugur' ); ?></div>
					<div class="gugur-hotkeys__item--shortcut">
						<span>Esc</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>
