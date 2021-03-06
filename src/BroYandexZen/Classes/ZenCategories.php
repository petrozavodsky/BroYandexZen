<?php

namespace BroYandexZen\Classes;
class ZenCategories
{

    public $base_name = 'ZenCategories';
    public $field_name = "_zen_category";
    public $categories;


    public function __construct($categories)
    {
        $this->categories = $categories;
        add_action('add_meta_boxes', [$this, 'fields'], 1);
        add_action('save_post', [$this, 'fields_update'], 0);
        add_action('BroYandexZenFeedFields_post_args', [$this, 'exclude_empty'], 10);
    }

    public function exclude_empty($args)
    {

        $args['meta_query'] = [
            'relation' => 'AND',
            'exclude_zen' => [
                'key' => $this->field_name,
                'value' => 'none',
                'type' => 'CHAR',
                'compare' => 'NOT IN',
            ],
            'exist'=>[
                'key' => $this->field_name,
                'compare' => 'EXISTS'
            ]
        ];

        return $args;
    }

    public function fields()
    {
        add_meta_box(
            $this->base_name . '_fields',
            'Категория в ZEN YANDEX',
            function ($post) {
                ?>
                <p>
                    <label>
                        Выберите категорию:
                        <br>
                        <select name="<?php echo $this->base_name; ?>[<?php echo $this->field_name; ?>]"
                                required="required">
                            <?php $current = get_post_meta($post->ID, $this->field_name, 1);
                            if ($current == "") {
                                $current = $this->categories[0];
                            }
                            ?>
                            <option value="none" <?php selected($current, 'none') ?> ><?php echo 'Не добавзять в zen'; ?></option>
                            <?php foreach ($this->categories as $category): ?>
                                <option value="<?php echo $category; ?>" <?php selected($current, $category) ?> ><?php echo $category; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>
                </p>

                <input type="hidden" name="<?php echo $this->base_name; ?>_fields_nonce"
                       value="<?php echo wp_create_nonce(__FILE__); ?>"/>
                <?php
            },
            ['post'],
            'side',
            'high');
    }

    public function fields_update($post_id)
    {
        if (!isset($_POST[$this->base_name . '_fields_nonce']) || !wp_verify_nonce($_POST[$this->base_name . '_fields_nonce'], __FILE__)) {
            return false;
        };
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return false;
        }
        if (!current_user_can('edit_post', $post_id)) {
            return false;
        }

        if (!isset($_POST[$this->base_name])) {
            return false;
        }

        $_POST[$this->base_name] = array_map('trim', $_POST[$this->base_name]);
        foreach ($_POST[$this->base_name] as $key => $value) {
            if (empty($value)) {
                delete_post_meta($post_id, $key);
                continue;
            }

            update_post_meta($post_id, $key, $value);
        }

        return $post_id;
    }


}