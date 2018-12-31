<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>RADIOS</title>
		<meta name="viewport" content="initial-scale=1.0, maximum-scale=1.0, width=device-width">
		<link rel="stylesheet" href="/style.css">
	</head>
	<body>
		<div class="table-title">
			<h3>LES RADIOS</h3>
		</div>
		<table class="table-fill" id="stations">
			<thead>
				<tr>
					<th class="text-left" data-sort-method="number" data-sort-default>
					</th>
					<th class="text-left">
						NOM
					</th>
					<th class="text-left">
					    GROUPE
					</th>
					<th class="text-left">
					    PAYS
					</th>
				</tr>
			</thead>
			<tbody class="table-hover">
			<?php
                define("SQL_DSN", "mysql:host=localhost;dbname=radios");
                define("SQL_USER", "radios");
                define("SQL_PASSWORD", "f6N5xW6GwHGQN2mS");
//                define("SQL_DSN", "mysql:host=localhost;port=8889;dbname=radios"); 
//                define("SQL_USER", "zeradio");
//                define("SQL_PASSWORD", "7rsPmYxQPpI0HJIZ");
                try{
                  $SQL = new PDO(SQL_DSN, SQL_USER, SQL_PASSWORD);
                  $SQL->exec("SET CHARACTER SET utf8");
                }catch(Exception $e){
                  die("Erreur : ".$e);
                }
                $Radios = $SQL->prepare("SELECT stations.id, stations.nom, stations.stream_url, stations.logo_url, groupes.nom as groupe, pays.nom as pays FROM stations JOIN groupes ON stations.groupes_id = groupes.id JOIN pays ON stations.pays_id = pays.id ORDER BY stations.id");
                $Radios->execute();
                $Radios = $Radios->fetchAll();
                foreach($Radios as $Radio){
            ?>
				<tr class="station" data-id="<?= $Radio['id']; ?>" data-stream="<?= $Radio['stream_url']; ?>">
					<td class="text-center" data-attr="logo" data-sort="<?= $Radio['id']; ?>"><img width="50" src="<?= $Radio['logo_url']; ?>" alt="<?= $Radio['nom']; ?>"></td>
					<td class="text-left" data-attr="nom"><?= $Radio['nom']; ?></td>
					<td class="text-left" data-attr="groupe"><?= $Radio['groupe']; ?></td>
					<td class="text-left" data-attr="pays"><?= $Radio['pays']; ?></td>
				</tr>
            <?php
                }
            ?>
			</tbody>
		</table>
        <div class="player" style="display:none;">
            <div class="station">
                <div class="logo_ib">
                    <div class="logo"><img src="" alt=""></div>
                </div>
                <div class="name"></div>
            </div>
            <div class="controls">
                <div class="slider">
                    <div class="volume_button" data-side="moins">-</div>
                    <input type="range" class="bar" max="100" min="0" value="50" step="1">
                    <div class="volume_button" data-side="plus">+</div>
                </div>
                <div class="play_pause">
                    <div class="play" style="display:none;"></div>
                    <div class="pause">
                        <div class="pause_item"></div><!--
                        --><div class="pause_item"></div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/jquery.min.js"></script>
		<script src="/tablesort.js"></script>
        <script src="/tablesort.number.js"></script>
        <script>
            new Tablesort(document.getElementById('stations'))
            
            var player = document.createElement("audio")
            player.volume = 0.5;
            
            var play = function(){
                if(player.paused){
                    player.play()
                }else{
                    player.pause()
                }
            }
            player.onplaying = function(){
                $(".play").hide()
                $(".pause").show()
            }
            
            player.onpause = function(){
                $(".play").show()
                $(".pause").hide()
            }
            $(".player").on("click", ".play_pause", function(){
                play()
            })
            $("#stations").on("click", ".station", function(){
                $(".player").fadeIn();
                $(".player .logo img").attr("src", $(this).find("[data-attr=logo] img").attr("src"))
                $(".player .name").text($(this).find("[data-attr=nom]").text())
                player.setAttribute("src", $(this).attr("data-stream"))
                player.load()
                play()
            })
            var changeVolume = function(vol){
                player.volume = parseInt(vol) / 100;
                $(".bar").val(vol)
            }
            $(".volume_button[data-side=plus]").click(function(){
                changeVolume(parseInt($(".bar").val()) + 10)
            })
            $(".volume_button[data-side=moins]").click(function(){
                changeVolume(parseInt($(".bar").val()) - 10)
            })
            $(".bar").change(function(){
                changeVolume(parseInt($(".bar").val()))
            })
        </script>
	</body>
