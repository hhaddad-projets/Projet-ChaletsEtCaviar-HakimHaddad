<?php

/*
Widget Name: Services
Description: Capture services in a multi-column grid.
Author: LiveMesh
Author URI: https://www.livemeshthemes.com
*/
namespace LivemeshAddons\Widgets;

use  Elementor\Widget_Base ;
use  Elementor\Controls_Manager ;
use  Elementor\utils ;
use  Elementor\Scheme_Color ;
use  Elementor\Group_Control_Typography ;
use  Elementor\Group_Control_Image_Size ;
use  Elementor\Scheme_Typography ;
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
// Exit if accessed directly
class LAE_Services_Widget extends Widget_Base
{
    public function get_name()
    {
        return 'lae-services';
    }
    
    public function get_title()
    {
        return __( 'Services', 'livemesh-el-addons' );
    }
    
    public function get_icon()
    {
        return 'fa fa-clone';
    }
    
    public function get_categories()
    {
        return array( 'livemesh-addons' );
    }
    
    public function get_script_depends()
    {
        return [ 'lae-frontend-scripts', 'lae-waypoints' ];
    }
    
    protected function _register_controls()
    {
        $this->start_controls_section( 'section_services', [
            'label' => __( 'Services', 'livemesh-el-addons' ),
        ] );
        $style_options = [
            'style1' => __( 'Style 1', 'livemesh-el-addons' ),
            'style2' => __( 'Style 2', 'livemesh-el-addons' ),
            'style3' => __( 'Style 3', 'livemesh-el-addons' ),
        ];
        $this->add_control( 'style', [
            'type'         => Controls_Manager::SELECT,
            'label'        => __( 'Choose Style', 'livemesh-el-addons' ),
            'default'      => 'style1',
            'options'      => $style_options,
            'prefix_class' => 'lae-services-',
        ] );
        $this->add_responsive_control( 'per_line', [
            'label'              => __( 'Columns per row', 'livemesh-el-addons' ),
            'type'               => Controls_Manager::SELECT,
            'default'            => '3',
            'tablet_default'     => '2',
            'mobile_default'     => '1',
            'options'            => [
            '1' => '1',
            '2' => '2',
            '3' => '3',
            '4' => '4',
            '5' => '5',
            '6' => '6',
        ],
            'frontend_available' => true,
        ] );
        $this->add_group_control( Group_Control_Image_Size::get_type(), [
            'name'        => 'thumbnail_size',
            'label'       => __( 'Icon Image Size', 'livemesh-el-addons' ),
            'description' => __( 'Size of icon image if chosen for display', 'livemesh-el-addons' ),
            'default'     => 'large',
        ] );
        $this->add_control( 'services', [
            'type'        => Controls_Manager::REPEATER,
            'default'     => [ [
            'service_title'   => __( 'Web Design', 'livemesh-el-addons' ),
            'icon_type'       => 'icon',
            'icon'            => 'fa fa-bell-o',
            'service_excerpt' => 'Curabitur ligula sapien, tincidunt non, euismod vitae, posuere imperdiet, leo. Donec venenatis vulputate lorem. In hac habitasse aliquam.',
        ], [
            'service_title'   => __( 'SEO Services', 'livemesh-el-addons' ),
            'icon_type'       => 'icon',
            'icon'            => 'fa fa-laptop',
            'service_excerpt' => 'Suspendisse nisl elit, rhoncus eget, elementum ac, condimentum eget, diam. Phasellus nec sem in justo pellentesque facilisis platea dictumst.',
        ], [
            'service_title'   => __( 'Brand Marketing', 'livemesh-el-addons' ),
            'icon_type'       => 'icon',
            'icon'            => 'fa fa-toggle-off',
            'service_excerpt' => 'Nunc egestas, augue at pellentesque laoreet, felis eros vehicula leo, at malesuada velit leo quis pede. Etiam ut purus mattis mauris sodales.',
        ] ],
            'fields'      => [
            [
            'name'        => 'service_title',
            'label'       => __( 'Service Title', 'livemesh-el-addons' ),
            'type'        => Controls_Manager::TEXT,
            'label_block' => true,
            'default'     => __( 'My service title', 'livemesh-el-addons' ),
            'dynamic'     => [
            'active' => true,
        ],
        ],
            [
            'name'    => 'icon_type',
            'label'   => __( 'Icon Type', 'livemesh-el-addons' ),
            'type'    => Controls_Manager::SELECT,
            'default' => 'icon',
            'options' => [
            'none'       => __( 'None', 'livemesh-el-addons' ),
            'icon'       => __( 'Icon', 'livemesh-el-addons' ),
            'icon_image' => __( 'Icon Image', 'livemesh-el-addons' ),
        ],
        ],
            [
            'name'        => 'icon_image',
            'label'       => __( 'Service Image', 'livemesh-el-addons' ),
            'type'        => Controls_Manager::MEDIA,
            'default'     => [
            'url' => Utils::get_placeholder_image_src(),
        ],
            'label_block' => true,
            'condition'   => [
            'icon_type' => 'icon_image',
        ],
            'dynamic'     => [
            'active' => true,
        ],
        ],
            [
            'name'        => 'icon',
            'label'       => __( 'Service Icon', 'livemesh-el-addons' ),
            'type'        => Controls_Manager::ICON,
            'label_block' => true,
            'default'     => '',
            'condition'   => [
            'icon_type' => 'icon',
        ],
        ],
            [
            'name'        => 'service_excerpt',
            'label'       => __( 'Service description', 'livemesh-el-addons' ),
            'type'        => Controls_Manager::TEXTAREA,
            'default'     => __( 'Service description goes here', 'livemesh-el-addons' ),
            'label_block' => true,
            'dynamic'     => [
            'active' => true,
        ],
        ],
            [
            "type"    => Controls_Manager::SELECT,
            "name"    => "widget_animation",
            "label"   => __( "Animation Type", "livemesh-el-addons" ),
            'options' => lae_get_animation_options(),
            'default' => 'none',
        ]
        ],
            'title_field' => '{{{ service_title }}}',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_service_title', [
            'label' => __( 'Service Title', 'livemesh-el-addons' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'title_tag', [
            'label'   => __( 'Title HTML Tag', 'livemesh-el-addons' ),
            'type'    => Controls_Manager::SELECT,
            'options' => [
            'h1'  => __( 'H1', 'livemesh-el-addons' ),
            'h2'  => __( 'H2', 'livemesh-el-addons' ),
            'h3'  => __( 'H3', 'livemesh-el-addons' ),
            'h4'  => __( 'H4', 'livemesh-el-addons' ),
            'h5'  => __( 'H5', 'livemesh-el-addons' ),
            'h6'  => __( 'H6', 'livemesh-el-addons' ),
            'div' => __( 'div', 'livemesh-el-addons' ),
        ],
            'default' => 'h3',
        ] );
        $this->add_control( 'title_color', [
            'label'     => __( 'Color', 'livemesh-el-addons' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .lae-services .lae-service .lae-service-text .lae-title' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'title_typography',
            'selector' => '{{WRAPPER}} .lae-services .lae-service .lae-service-text .lae-title',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_service_text', [
            'label' => __( 'Service Text', 'livemesh-el-addons' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'text_color', [
            'label'     => __( 'Color', 'livemesh-el-addons' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .lae-services .lae-service .lae-service-text .lae-service-details' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_group_control( Group_Control_Typography::get_type(), [
            'name'     => 'text_typography',
            'selector' => '{{WRAPPER}} .lae-services .lae-service .lae-service-text .lae-service-details',
        ] );
        $this->end_controls_section();
        $this->start_controls_section( 'section_service_icon', [
            'label' => __( 'Service Icons', 'livemesh-el-addons' ),
            'tab'   => Controls_Manager::TAB_STYLE,
        ] );
        $this->add_control( 'icon_size', [
            'label'      => __( 'Icon or Icon Image size in pixels', 'livemesh-el-addons' ),
            'type'       => Controls_Manager::SLIDER,
            'size_units' => [ 'px', '%', 'em' ],
            'range'      => [
            'px' => [
            'min' => 6,
            'max' => 300,
        ],
        ],
            'selectors'  => [
            '{{WRAPPER}} .lae-services .lae-service .lae-image-wrapper img' => 'width: {{SIZE}}{{UNIT}};',
            '{{WRAPPER}} .lae-services .lae-service .lae-icon-wrapper span' => 'font-size: {{SIZE}}{{UNIT}};',
        ],
        ] );
        $this->add_control( 'icon_color', [
            'label'     => __( 'Icon Custom Color', 'livemesh-el-addons' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .lae-services .lae-service .lae-icon-wrapper span' => 'color: {{VALUE}};',
        ],
        ] );
        $this->add_control( 'hover_color', [
            'label'     => __( 'Icon Hover Color', 'livemesh-el-addons' ),
            'type'      => Controls_Manager::COLOR,
            'selectors' => [
            '{{WRAPPER}} .lae-services .lae-service .lae-icon-wrapper span:hover' => 'color: {{VALUE}};',
        ],
        ] );
        $this->end_controls_section();
    }
    
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $settings = apply_filters( 'lae_services_' . $this->get_id() . '_settings', $settings );
        $output = '<div class="lae-services lae-' . $settings['style'] . ' lae-grid-container ' . lae_get_grid_classes( $settings ) . '">';
        foreach ( $settings['services'] as $service ) {
            list( $animate_class, $animation_attr ) = lae_get_animation_atts( $service['widget_animation'] );
            $child_output = '<div class="lae-grid-item lae-service-wrapper">';
            $child_output .= '<div class="lae-service ' . $animate_class . '" ' . $animation_attr . '>';
            
            if ( $service['icon_type'] == 'icon_image' ) {
                
                if ( !empty($service['icon_image']) ) {
                    $child_output .= '<div class="lae-image-wrapper">';
                    $image_html = lae_get_image_html( $service['icon_image'], 'thumbnail_size', $settings );
                    $child_output .= $image_html;
                    $child_output .= '</div>';
                }
            
            } elseif ( $service['icon_type'] == 'icon' ) {
                $child_output .= '<div class="lae-icon-wrapper">';
                $child_output .= '<span class="' . esc_attr( $service['icon'] ) . '"></span>';
                $child_output .= '</div>';
            }
            
            $child_output .= '<div class="lae-service-text">';
            $child_output .= '<' . $settings['title_tag'] . ' class="lae-title">' . esc_html( $service['service_title'] ) . '</' . $settings['title_tag'] . '>';
            $child_output .= '<div class="lae-service-details">' . do_shortcode( wp_kses_post( $service['service_excerpt'] ) ) . '</div>';
            $child_output .= '</div><!-- .lae-service-text -->';
            $child_output .= '</div><!-- .lae-service -->';
            $child_output .= '</div><!-- .lae-service-wrapper -->';
            $output .= apply_filters(
                'lae_service_item_output',
                $child_output,
                $service,
                $settings
            );
        }
        $output .= '</div><!-- .lae-services -->';
        $output .= '<div class="lae-clear"></div>';
        echo  apply_filters( 'lae_services_output', $output, $settings ) ;
    }
    
    protected function content_template()
    {
    }

}