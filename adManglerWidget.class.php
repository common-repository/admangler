<?php

    class AdManglerWidget extends WP_Widget
    {

        function __construct()
        {
            parent::__construct( 'AdManglerWidget', __( 'AdMangler Widget', 'admangler' ), array('description' => __( 'Admangler Widget', 'admangler_domain' )) );
        }

        function widget( $args, $instance )
        {
            // What the widget will output in the front-end
            extract( $args );
            $title    = $instance['title'];
            $width    = $instance['width'];
            $height   = $instance['height'];
            $position = $instance['position'];


            $options           = new stdClass();
            $options->width    = $width;
            $options->height   = $height;
            $options->position = $position;
            $options->ajaxAd   = filter_var( get_option( "AdMangler_cache_buster" ), FILTER_VALIDATE_BOOLEAN );

            global $adMangler;
            $content = $adMangler->get_ad( $options ); // Here we get the post's excerpt
            ?>
            <?php echo $before_widget; ?>
            <?php echo $before_title . $title . $after_title; ?>

            <p><?php echo $content; ?></p>

            <?php echo $after_widget; ?>
            <?php
        }

        // When the Widget (No editing needed)
        function update( $new_instance, $old_instance )
        {
            return $new_instance;
        }

        function form( $instance )
        {
            // Widget Settings in the widgets back-end
            $title    = esc_attr( $instance['title'] );
            $width    = esc_attr( $instance['width'] );
            $height   = esc_attr( $instance['height'] );
            $position = esc_attr( $instance['position'] );
            ?>
            <p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'admangler_domain' ); ?></label> <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>
            <p>
                <label for="<?php echo $this->get_field_id( 'width' ); ?>"><?php _e( 'Width', 'admangler_domain' ); ?></label> <input size="3" id="<?php echo $this->get_field_id( 'width' ); ?>" name="<?php echo $this->get_field_name( 'width' ); ?>" type="text" value="<?php echo $width; ?>" />
                <label for="<?php echo $this->get_field_id( 'height' ); ?>"><?php _e( 'Height', 'admangler_domain' ); ?></label> <input size="3" id="<?php echo $this->get_field_id( 'height' ); ?>" name="<?php echo $this->get_field_name( 'height' ); ?>" type="text" value="<?php echo $height; ?>" />
            </p>
            <p><label for="<?php echo $this->get_field_id( 'Position' ); ?>"><?php _e( 'Position', 'admangler_domain' ); ?></label> <input size="3" id="<?php echo $this->get_field_id( 'position' ); ?>" name="<?php echo $this->get_field_name( 'position' ); ?>" type="text" value="<?php echo $position; ?>" /></p>
            <?php
        }

    }
