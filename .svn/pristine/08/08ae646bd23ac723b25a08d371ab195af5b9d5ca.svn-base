<?php

/**
 * 格式化活动模块
 */
class ActivityModular {

    public function __construct()
    {

    }

    /**
     * 格式化后台活动模块html输出
     * @param array $activityModularList 模块列表[['modular_type'=>'xxx','modular_id'=>'xxx'],..]
     * @return string
     */
    public function formatActivityModular($activityModularList) {
        
        if ( !$activityModularList || !is_array($activityModularList) ) {
            return '';
        }

        $return = '';
        foreach ($activityModularList as $activityModular) {
            $modularType = $activityModular['modular_type'];
            $modularId = $activityModular['modular_id'];
            $sort = $activityModular['sort']?:0;

            $inputSort = '<input type="hidden" name="sort[' . $modularId . ']" value="' . $sort . '" class="sort" >';

            switch( $modularType ) {
                case 'one-img': // 单图片模块
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="one-img" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="one-img">
                            <img src="' . HtmlTool::getStaticFile('special_subject/pos.png?2') . '" />
                        </div>
                    </div>';
                    break;
                case 'two-img': // 双图片模块
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="two-img" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="two-img clearfix">
                            <img src="' . HtmlTool::getStaticFile('special_subject/two-img.png?2') . '" />
                        </div>
                    </div>';
                    break;
                case 'add-text': // 文本模块
                    $return .= 
                    '<div id="modular_' . $modularId . '" data-type="add-text" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="intro">
                            <p>An introduction to the topic, the wrap height should be variable with contents</p>
                        </div>
                    </div>';
                    break;
                case 'anchor': // 锚点TAB
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="anchor" data-modular_id="'. $modularId .'" class="nav unit">
                        ' . $inputSort . '
                        <div class="wrap clearfix">
                            <a class="active" href="javascript:void(0);">锚点TAB</a>
                            <a href="javascript:void(0);">锚点TAB</a>
                            <a href="javascript:void(0);">锚点TAB</a>
                            <a href="javascript:void(0);">锚点TAB</a>
                        </div>
                    </div>';
                    break;
                case 'tab': // 切换TAB
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="tab" data-modular_id="'. $modularId .'" class="nav unit">
                        ' . $inputSort . '
                        <div class="wrap clearfix">
                            <a class="active" href="javascript:void(0);">切换TAB</a>
                            <a href="javascript:void(0);">切换TAB</a>
                            <a href="javascript:void(0);">切换TAB</a>
                            <a href="javascript:void(0);">切换TAB</a>
                        </div>
                    </div>';
                    break;
                case 'list1': // 列表一
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="list1" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="list1 one-img">
                            <img src="' . HtmlTool::getStaticFile('special_subject/list1.png?2') . '" />
                        </div>
                    </div>';
                    break;
                case 'list2': // 列表二
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="list2" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="one-img">
                            <img src="' . HtmlTool::getStaticFile('special_subject/list2.png?2') . '" />
                        </div>
                    </div>';
                    break;
                case 'list3': // 列表三
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="list3" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="list3 one-img">
                            <img src="' . HtmlTool::getStaticFile('special_subject/list3.png?2') . '" />
                        </div>
                    </div>';
                    break;
                case 'list4': // 列表四
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="list4" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="list4 one-img">
                            <img src="' . HtmlTool::getStaticFile('special_subject/list4.png?2') . '" />
                        </div>
                    </div>';
                    break;
                case 'list5': // 列表五
                    $return .=
                    '<div id="modular_' . $modularId . '" data-type="list5" data-modular_id="'. $modularId .'" class="unit">
                        ' . $inputSort . '
                        <div class="list5 one-img">
                            <img src="' . HtmlTool::getStaticFile('special_subject/list5.png?2') . '" />
                        </div>
                    </div>';
                    break;

            }
        }

        return $return;
    }
}