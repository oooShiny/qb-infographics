<?php
    $qb_folders = scandir('qbs'); 
    $qbs = [];
    $qbtds = [];
    $pick6s = [];
    $p6count = [];
    $split = [];
    $splitcount = [];
    $receivers = [];
    $path = explode('/', $_SERVER['REQUEST_URI']);

    foreach ($qb_folders as $qb) {
        if (strlen($qb) > 2) {
            $nav[] = [
                'url' => $qb,
                'name' => str_replace('-', ' ', $qb),
            ];
            if ($path[1] == $qb) {
                $qbtds[$qb] = 0;
                $p6count[$qb] = 0;
                $receivers[$qb] = 0;
                $qb_folder = 'qbs/' . $qb;
                $qb_files = scandir($qb_folder);
                $sub_folders = ['defenders', 'split'];
                foreach ($qb_files as $img) {
                    if (strlen($img) > 2 && !in_array($img, $sub_folders)) {
                        $img_array = explode('-', $img);
                        $tds = array_shift($img_array);
                        $receiver = implode('-', $img_array);
                        $qbs[$qb][$tds][] = $receiver;
                        $qbtds[$qb] += $tds;
                        $receivers[$qb] += 1;
                    }
                    elseif ($path[2] == '6' && $img == 'defenders') {
                        $pick6_folder = 'qbs/' . $qb . '/defenders';
                        $pick6_files = scandir($pick6_folder);
                        foreach ($pick6_files as $p6) {
                            if (strlen($p6) > 2) {
                                $p6_array = explode('-', $p6);
                                $tds = array_shift($p6_array);
                                $defender = implode('-', $p6_array);
                                $pick6s[$qb][$tds][] = $defender;
                                $p6count[$qb] += $tds;
                            }
                        }
                    }
                    elseif ($path[2] == 'split' && $img == 'split') {
                        $split_folder = 'qbs/' . $qb . '/split';
                        $split_files = scandir($split_folder);
                        foreach ($split_files as $sp) {
                            if (strlen($sp) > 2) {
                                $sp_array = explode('-', $sp);
                                $tds = array_shift($sp_array);
                                $defender = implode('-', $sp_array);
                                $split[$qb][$tds][] = $defender;
                                $splitcount[$qb] += $tds;
                            }
                        }
                    }
                }
                krsort($qbs[$qb], SORT_NUMERIC);
                krsort($pick6s[$qb], SORT_NUMERIC);
                krsort($split[$qb], SORT_NUMERIC);
            }
        }
    }

?>
<!doctype html>
<html>
    <head>
        <title>QB TDs</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="icon" href="/icon.ico" sizes="any"><!-- 32×32 -->
        <link rel="icon" href="/icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-icon.png"><!-- 180×180 -->
        <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="/qb-colors.css">
        <script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
    </head>
    <body class="container mx-auto bg-gray-400">
        <h1 class="text-5xl text-center"><a href="/">Quarterback TDs of the NFL</a></h1>
        <!-- Navigation -->
        <nav>
            <ul class="flex gap-5 justify-center list-none py-5">
                <?php foreach ($nav as $item): ?>
                    <li class="capitalize"><a href="/<?php print $item['url']; ?>"><?php print $item['name']; ?></a></li>
                <?php endforeach; ?>
            </ul>
        </nav>
        <!-- Content -->
        <div id="poster">
            <?php if (!empty($path[1])): ?>
                <?php if ($path[2] == 'split'): ?>
                    <?php foreach ($split as $qb => $td_passes): ?>
                        <?php $qb_name = str_replace('-', ' ', $qb); ?>
                        <h2 class="capitalize qb-name relative text-5xl p-5 text-center <?php print $qb; ?>">
                            <?php print $qb_name . '\'s ' . $qbtds[$qb]; ?> TD Passes to <?php print $receivers[$qb]; ?> receivers
                        </h2>
                        <div class="flex flex-wrap gap-2 items-baseline justify-evenly isotope">
                            <?php foreach ($td_passes as $t => $rec ): ?>
                                
                                <?php foreach ($rec as $r) : ?>
                                    <?php 
                                        $img_src = '/qbs/' . $qb . '/split/' . $t . '-' . $r; 
                                        $h = get_height_from_tds($t);
                                        $h = $h . 'rem';
                                    ?>

                                    <div class="player-item qb-<?php print $qb; ?>">
                                        <img src="<?php print $img_src; ?>" class="object-cover" style="height: <?php print $h; ?>">
                                    </div>

                                    
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php foreach ($qbs as $qb => $td_passes): ?>
                        <?php $qb_name = str_replace('-', ' ', $qb); ?>
                        <h2 class="capitalize qb-name relative text-5xl p-5 text-center <?php print $qb; ?>">
                            <?php print $qb_name . '\'s ' . $qbtds[$qb]; ?> TD Passes to <?php print $receivers[$qb]; ?> receivers
                        </h2>
                        <div class="flex flex-wrap gap-2 items-baseline justify-evenly isotope">
                            <?php foreach ($td_passes as $t => $rec ): ?>
                                
                                <?php foreach ($rec as $r) : ?>
                                    <?php 
                                        $img_src = '/qbs/' . $qb . '/' . $t . '-' . $r; 
                                        $h = get_height_from_tds($t);
                                        $h = $h . 'rem';
                                    ?>

                                    <div class="player-item qb-<?php print $qb; ?>">
                                        <img src="<?php print $img_src; ?>" class="object-cover" style="height: <?php print $h; ?>">
                                    </div>

                                    
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php if (!empty($pick6s[$qb])): ?>
                            <h2 class="capitalize qb-name relative text-5xl text-center p-5 my-10 <?php print $qb; ?>"><?php print $p6count[$qb]; ?> Pick Sixes</h2>
                            <div class="flex flex-wrap gap-2 items-baseline justify-evenly isotope">
                                <?php foreach ($pick6s as $qb => $p6s): ?>
                                    <?php foreach ($p6s as $t => $rec ): ?>
                                        
                                        <?php foreach ($rec as $r) : ?>
                                            <?php 
                                                $img_src = '/qbs/' . $qb . '/defenders/' . $t . '-' . $r; 
                                                $h = get_height_from_tds($t);
                                                $h = $h . 'rem';
                                            ?>

                                            <div class="player-item qb-<?php print $qb; ?>">
                                                <img src="<?php print $img_src; ?>" class="object-cover" style="height: <?php print $h; ?>">
                                            </div>

                                            
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>

            <?php else: ?>
                <script>
                    var elem = document.querySelector('.isotope');
                    var iso = new Isotope( elem, {
                        // options
                        itemSelector: '.player-item',
                        layoutMode: 'fitRows'
                    });
                </script>
                <p class="p-10">
                    I wanted to see how the top quarterbacks spread the ball around when throwing TD passes. 
                    Some QBs relied more heavily on a few receivers. Others have spread the ball out to anyone 
                    and everyone. Click on one of the QBs to see how they break down and compare to each other.
                </p>
                <div class="flex flex-wrap gap-2 items-baseline justify-evenly">
                    <?php foreach ($nav as $item): ?>
                        <div class="p-5 text-xl <?php print $item['url']; ?>"><a href="/<?php print $item['url']; ?>" class="capitalize"><?php print $item['name']; ?></a></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
    </body>
</html>


<?php 

function get_height_from_tds($tds) {
    $max_h = 26;
    $min_h = 8;
    $max_tds = 120;
    $min_tds = 1;

    $result = ($tds - $min_tds) * ($max_h - $min_h) / ($max_tds - $min_tds) + $min_h;
    $result = (2 * sqrt(153 * $tds + 1463))/sqrt(101);

    // if ($result < $min_h) {
    //   return $min_h;
    // } 
    // else if ($result > $max_h) {
    //   return $max_h;
    // }
    return $result;
}
?>