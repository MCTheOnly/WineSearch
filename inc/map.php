<?php
namespace WPC\Widgets;
use Elementor\Widget_Base;
use Elementor\Control_Manager;

defined( 'ABSPATH' ) or die('Nothing here...'); //Exit if accessed directly

class Map extends Widget_Base
{
    public function get_name()
    {
        return 'WLC Map';
    }
    public function get_title()
    {
        return 'WLC Map';
    }
    public function get_icon()
    {
        return 'fa-solid fa-map';
    }
    public function get_categories()
    {
        return ['general'];
    }
    protected function _register_controls()
    {
        $this-> start_controls_section(
            'section_content',
            [
                'label' => 'settings',
            ]
        );
        $this->add_control(
            'center-lat',
            [
                'name'        => 'center-lat',
                'label'       => esc_html__( 'Center-lat', 'plugin-name' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'List Item', 'plugin-name' ),
                'default'     => esc_html__( 'List Item', 'plugin-name' ),
            ]
        );
        $this->add_control(
            'center-lng',
            [
                'name'        => 'center-lng',
                'label'       => esc_html__( 'Center-lng', 'plugin-name' ),
                'type'        => \Elementor\Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'List Item', 'plugin-name' ),
                'default'     => esc_html__( 'List Item', 'plugin-name' ),
            ]
        );
        $this->add_control(
            'list',
            [
                'label'     => esc_html__( 'Lista', 'Marker' ),
                'type'      => \Elementor\Controls_Manager::REPEATER,
                'fields'    => [                 [
                            'name'        => 'Miejsce',
                            'label'       => esc_html__( 'Miejsce', 'plugin-name' ),
                            'type'        => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'List Item', 'plugin-name' ),
                            'default'     => esc_html__( 'List Item', 'plugin-name' ),
                        ],
                        [
                            'name'        => 'text',
                            'label'       => esc_html__( 'lat', 'plugin-name' ),
                            'type'        => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'List Item', 'plugin-name' ),
                            'default'     => esc_html__( 'List Item', 'plugin-name' ),
                        ],
                        [
                            'name'        => 'text2',
                            'label'       => esc_html__( 'lng', 'plugin-name' ),
                            'type'        => \Elementor\Controls_Manager::TEXT,
                            'placeholder' => esc_html__( 'List Item', 'plugin-name' ),
                            'default'     => esc_html__( 'List Item', 'plugin-name' ),
                        ],
                ],
                'default' => [
                    [
                        'text' => esc_html__( 'List Item #1', 'plugin-name' ),
                    ],
                    [
                        'text' => esc_html__( 'List Item #2', 'plugin-name' ),
                    ],
                ],
                'title_field' => '{{{ Miejsce }}}',
            ]
        );
        $this->end_controls_section();
    }
    //PHP RENDER
    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $this->add_inline_editing_attributes('label_heading', 'basic');
        $this->add_render_attribute(
            'label_heading',
            [
                'class' => ['map__label-heading'],
            ]
        );
        ?>
        <!-- HTML CONTENT -->
        <div class="map">
            <div class="map__content">
                <div class="map__content__heading">
                <?php $map_arr = [];
                foreach ( $settings['list'] as $index => $item ) : ?>
                <?php array_push($map_arr,[$item['text'],$item['text2']]); ?>
                <?php endforeach; ?>
                <?php $center_lat = $settings['center-lat']; ?>
                <?php $center_lng = $settings['center-lng']; ?>
                </div>
                <div id="map"></div>
                <script>
                    // Variables
                    let markers      = <?php echo json_encode($map_arr); ?>;
                    let position_lat = <?php echo json_encode($center_lat); ?>;
                    let position_lng = <?php echo json_encode($center_lng); ?>;
                    let newArray     = [];
                    
                    const convertArrayToObject = (array) => {
                        array.forEach(function(item) {
                            let initialValue = {
                                lat: null,
                                lng: null,
                            };
                            initialValue.lat = Number(item[0]);
                            initialValue.lng = Number(item[1]);
                            newArray.push(initialValue);
                        });
                    };
                    
                    convertArrayToObject(markers);
                    let map;
                    
                    function initMap() {
                        // Map options
                        let options = {
                            center: { lat: -34.397, lng: 150.644 },
                            zoom: 5,
                        }
                        options.center = { lat: Number(position_lat), lng: Number(position_lng) };
                        // New map
                        map = new google.maps.Map(document.getElementById("map"), options);
                        
                        // loop through markers
                        for(let i = 0; i < newArray.length; i++) {
                            addMarker(newArray[i]);
                        }
                        
                        // Add marker function
                        function addMarker(coords) {
                            let marker = new google.maps.Marker({
                            position: coords,
                            map,
                        });
                        }
                       
                    }
                    window.initMap = initMap;
                </script>
                <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCGcKnauNjFMHuAN8yPt6RDPQOMHeHgt30&callback=initMap"></script>
            </div>
        </div>
        <?php
    }

    //JS RENDER
    protected function _content_template() {
        ?>
        <# if ( settings.list.length ) { #>
        <dl>
            <# _.each( settings.list, function( item ) { #>
                <dt class="elementor-repeater-item-{{ item._id }}">{{{ item.list_title }}}</dt>
                <dd>{{{ item.list_content }}}</dd>
            <# }); #>
        </dl>
        <# } #>
        <?php
    }
}
