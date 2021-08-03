const DELTA_SPAWN_BLOCK = 1;
const DELTA_SPAWN_COIN = 1;
const DELAY_BLOCK = 400;
const SPEED_PLAYER = 300;
var PiouPiou;
(function (PiouPiou_1) {
    function randomInteger(min, max) {
        return Math.floor(Math.random() * (max - min + 1)) + min;
    }
    class PiouPiou {
        constructor() {
            this.game = new Phaser.Game(500, "100%", Phaser.CANVAS, 'content', {
                preload: this.preload,
                create: this.create,
                update: this.update,
                render: this.render,
                spawnBlock: this.spawnBlock,
                spawnCoin: this.spawnCoin,
                checkLastRow: this.checkLastRow,
                toggleSpawnBlock: this.toggleSpawnBlock,
                endGame: this.endGame,
                restartGame: this.restartGame,
                handleCoinBlockCollision: this.handleCoinBlockCollision,
                handlePlayerBlockCollision: this.handlePlayerBlockCollision,
                handlePlayerCoinCollision: this.handlePlayerCoinCollision,
                handleDesktopInput: this.handleDesktopInput,
                handleMobileInput: this.handleMobileInput,
                dimBlock: this.dimBlock
            });
        }
        preload() {
            this.game.load.image('background', 'assets/sprites/background.png');
            this.game.load.image('mushroom', 'assets/sprites/mushroom.png');
            this.game.load.atlasJSONHash('player', '../../assets/player.png', '../../assets/player.json');
            this.game.load.image('block', 'assets/sprites/block.png');
            this.game.load.image('coin', 'assets/sprites/coin.png');
            this.game.load.image('button', '../../assets/button.png');
            this.game.load.audio('coin-drop', 'assets/audio/coin-drop.mp3');
            this.game.load.audio('pickup', 'assets/audio/pickup.mp3');
            this.game.load.audio('coin-break', 'assets/audio/coin-break.wav');
            this.game.load.audio('gameover', 'assets/audio/gameover.wav');
            this.game.load.audio('gamestart', 'assets/audio/gamestart.mp3');
        }
        create() {
            this.game.physics.startSystem(Phaser.Physics.ARCADE);
            this.game.physics.arcade.enable(this);
            // this.game.stage.backgroundColor = '#736357';
            var background = this.game.add.sprite(0, 0, 'background');
            // background.anchor.setTo(1, 1);
            if (this.game.width > this.game.height) {
                background.width = this.game.width;
                background.scale.y = background.scale.x;
            }
            else {
                background.height = this.game.height;
                background.scale.x = background.scale.y;
            }
            this.game.physics.arcade.gravity.y = 1500;
            this.controls = {
                up: false,
                right: false,
                down: false,
                left: false
            };
            var upKey = this.game.input.keyboard.addKey(Phaser.Keyboard.UP);
            var rightKey = this.game.input.keyboard.addKey(Phaser.Keyboard.RIGHT);
            var downKey = this.game.input.keyboard.addKey(Phaser.Keyboard.DOWN);
            var leftKey = this.game.input.keyboard.addKey(Phaser.Keyboard.LEFT);
            var rKey = this.game.input.keyboard.addKey(Phaser.Keyboard.R);
            rightKey.onDown.add(() => this.controls.right = true, this);
            rightKey.onUp.add(() => this.controls.right = false, this);
            leftKey.onDown.add(() => this.controls.left = true, this);
            leftKey.onUp.add(() => this.controls.left = false, this);
            rKey.onDown.add(() => {
                if (this.gameOver && this.game.device.desktop)
                    this.restartGame();
            });
            this.game.scale.scaleMode = Phaser.ScaleManager.RESIZE;
            // UI
            // this.timerText = this.game.add.text(this.game.world.centerX - 50, 60, '0s', { font: "60px Arial", fill: "#ffffff", align: "center" });
            // this.timerText.anchor.setTo(0.5, 0.5);
            this.scoreText = this.game.add.text(60, 60, '0', {});
            this.scoreText.anchor.setTo(0.5, 0.5);
            this.gameoverText = this.game.add.text(this.game.world.centerX, this.game.world.centerY, 'Game Over', { font: "64px Arial", fill: "#ffffff", align: "center" });
            this.gameoverText.anchor.setTo(0.5, 0.5);
            // this.restartText = this.game.add.text(this.game.world.centerX, this.game.world.centerY + 50, 'Press R to play again', { font: "18px Arial", fill: "#ffffff", align: "center" });
            // this.restartText.anchor.setTo(0.5, 0.5);
            // if (!this.game.device.desktop) this.restartText.setText('Tap anywhere to play again');
            var multiplier = this.game.device.desktop ? 1 : 2;
            var button = this.game.add.sprite(this.game.world.centerX, this.game.world.centerY + 50, 'button');
            button.width *= multiplier;
            button.height *= multiplier;
            button.position.x -= button.width / 2;
            button.inputEnabled = true;
            button.events.onInputDown.add(() => {
                this.restartGame();
            });
            var backToMapText = this.game.add.text(0, 0, "Play Again", { font: (24 * multiplier) + "px Arial", fill: "#ffffff" });
            backToMapText.position.x = button.x + (button.width - backToMapText.width) / 2;
            backToMapText.position.y = button.y + (button.height - backToMapText.height) / 2;
            this.playAgainButton = this.game.add.group();
            this.playAgainButton.add(button);
            this.playAgainButton.add(backToMapText);
            var button = this.game.add.sprite(this.game.world.centerX, this.game.world.centerY + 250, 'button');
            button.width *= multiplier;
            button.height *= multiplier;
            button.position.x -= button.width / 2;
            button.inputEnabled = true;
            button.events.onInputDown.add(() => {
                window.location.href = "../..";
            });
            var backToMapText = this.game.add.text(0, 0, "Back to Map", { font: (24 * multiplier) + "px Arial", fill: "#ffffff" });
            backToMapText.position.x = button.x + (button.width - backToMapText.width) / 2;
            backToMapText.position.y = button.y + (button.height - backToMapText.height) / 2;
            this.backToMapButton = this.game.add.group();
            this.backToMapButton.add(button);
            this.backToMapButton.add(backToMapText);
            this.coinSound = this.game.add.audio('coin-drop');
            this.pickupSound = this.game.add.audio('pickup');
            this.coinBreakSound = this.game.add.audio('coin-break');
            this.gameoverSound = this.game.add.audio('gameover');
            this.gamestartSound = this.game.add.audio('gamestart');
            this.restartGame();
        }
        restartGame() {
            this.scoreText.position.setTo(60, 60);
            this.scoreText.setStyle({ font: "60px Arial", fill: "#ffffff", align: "center" });
            this.scoreText.setText('0');
            this.lastSpawnBlock = this.game.time.totalElapsedSeconds();
            this.lastSpawnCoin = this.game.time.totalElapsedSeconds();
            this.nextCoinTime = DELTA_SPAWN_COIN;
            this.nextBlockTime = DELTA_SPAWN_BLOCK;
            this.gamestartSound.play();
            this.restartTime = this.game.time.totalElapsedSeconds();
            // Player
            this.player = this.game.add.sprite(350, 0, 'player');
            this.player.animations.add('down', [4, 3, 2, 1].map(n => 'down_' + n + '.png'), 10, true);
            this.player.animations.add('left', [4, 3, 2, 1].map(n => 'left_' + n + '.png'), 10, true);
            this.player.animations.add('right', [4, 3, 2, 1].map(n => 'right_' + n + '.png'), 10, true);
            this.player.animations.play('down');
            // this.player = this.game.add.sprite(350, 0, 'mushroom');
            this.game.physics.enable(this.player, Phaser.Physics.ARCADE);
            this.player.body.setSize(this.player.width, this.player.height, 0, +10);
            this.player.width = this.dimBlock() / 1.5;
            this.player.height = this.player.width;
            // this.player.position.y = this.game.world.height - this.player.height;
            this.player.position.y = this.game.world.height - this.player.height;
            this.player.anchor.setTo(0.5, 0.5);
            this.player.body.collideWorldBounds = true;
            this.canMove = true;
            // Blocks
            this.blocks = [[], [], [], [], [], [], [], [], []];
            this.blockDelay = DELAY_BLOCK;
            this.toggleSpawnBlock(true);
            // Coins
            this.coins = [];
            this.score = 0;
            this.gameoverText.visible = false;
            this.gameOver = false;
            this.playAgainButton.visible = false;
            this.backToMapButton.visible = false;
        }
        update() {
            if (this.gameOver) {
                return;
            }
            // UI
            var seconds = this.game.time.totalElapsedSeconds() - this.restartTime;
            // this.timerText.setText(parseInt(seconds+'', 10) + 's');
            this.scoreText.setText(this.score);
            // Blocks collision
            var collidedOnce = false;
            for (var i = 0; i < this.blocks.length; i++) {
                for (var j = 0; j < this.blocks[i].length; j++) {
                    var block = this.blocks[i][j];
                    var collided = this.game.physics.arcade.collide(this.player, block, null, null, this);
                    if (collided) {
                        this.handlePlayerBlockCollision(this.player, block, [i, j]);
                    }
                    collidedOnce = collidedOnce || collided;
                }
            }
            // Coins collision
            for (var i = 0; i < this.coins.length; i++) {
                var coin = this.coins[i];
                coin.body.velocity.y = 500;
                var collided = this.game.physics.arcade.overlap(this.player, coin, null, null, this);
                if (collided) {
                    this.handlePlayerCoinCollision(this.player, coin, i);
                }
            }
            for (var c = 0; c < this.coins.length; c++) {
                var coin = this.coins[c];
                for (var i = 0; i < this.blocks.length; i++) {
                    for (var j = 0; j < this.blocks[i].length; j++) {
                        var block = this.blocks[i][j];
                        var collided = this.game.physics.arcade.collide(block, coin, null, null, this);
                        if (collided) {
                            this.handleCoinBlockCollision(block, coin, c);
                        }
                    }
                }
            }
            var now = this.game.time.totalElapsedSeconds();
            if (now - this.lastSpawnBlock > this.nextBlockTime && this.canSpawnBlock) {
                this.spawnBlock();
                this.nextBlockTime *= 0.99;
                this.toggleSpawnBlock(false);
            }
            if (now - this.lastSpawnCoin > this.nextCoinTime) {
                this.spawnCoin();
            }
            if (this.game.device.desktop)
                this.handleDesktopInput();
            else
                this.handleMobileInput();
        }
        handleDesktopInput() {
            if (this.canMove) {
                var newVelX = 0;
                if (this.controls.right) {
                    // this.player.animations.play('right');
                    newVelX = SPEED_PLAYER;
                }
                else if (this.controls.left) {
                    // this.player.animations.play('left');
                    newVelX = -SPEED_PLAYER;
                    // this.player.scale.x = -Math.abs(this.player.scale.x);
                }
                else {
                    // this.player.animations.play('down');
                }
                this.player.body.velocity.x = newVelX;
            }
        }
        handleMobileInput() {
            if (this.canMove && this.game.input.pointer1.isDown) {
                var newVelX = 0;
                if (this.game.input.pointer1.position.x > this.game.world.centerX) {
                    // this.player.animations.play('right');
                    newVelX = SPEED_PLAYER;
                }
                else {
                    newVelX = -SPEED_PLAYER;
                    // this.player.animations.play('left');
                    // this.player.scale.x = -Math.abs(this.player.scale.x);
                }
                this.player.body.velocity.x = newVelX;
            }
            else {
                // this.player.animations.play('down');
                this.player.body.velocity.x = 0;
            }
        }
        endGame() {
            console.log('game over!');
            this.gameOver = true;
            this.player.kill();
            this.toggleSpawnBlock(false);
            this.gameoverSound.play();
            for (var i = 0; i < this.blocks.length; i++) {
                for (var j = 0; j < this.blocks[i].length; j++) {
                    var block = this.blocks[i][j];
                    block.kill();
                }
            }
            for (var i = 0; i < this.coins.length; i++) {
                this.coins[i].kill();
            }
            // this.gameoverText.visible = true;
            this.scoreText.position.x = this.game.world.centerX;
            this.scoreText.position.y = this.game.world.centerY - 100;
            var multiplier = this.game.device.desktop ? 1 : 2;
            this.scoreText.setStyle({
                font: (60 * multiplier) + "px Arial",
                fontWeight: "bold",
                fill: "#FFFFFF"
            });
            // this.restartText.visible = true;
            this.playAgainButton.visible = true;
            this.backToMapButton.visible = true;
        }
        handleCoinBlockCollision(block, coin, i) {
            console.log('block and coin');
            if (block['falling']) {
                coin.kill();
                this.coins.splice(i, 1);
                this.coinBreakSound.play();
            }
        }
        handlePlayerCoinCollision(player, coin, i) {
            coin.kill();
            this.coins.splice(i, 1);
            this.score++;
            this.pickupSound.play();
            console.log('score: ' + this.score);
        }
        handlePlayerBlockCollision(player, block, blockData) {
            console.log("collision");
            if (block.position.y + block.height < player.position.y + player.height / 2) {
                this.endGame();
            }
            else if (!player.body.touching.left && !player.body.touching.right) {
            }
            else {
                if (!this.canMove || block['falling'])
                    return;
                this.canMove = false;
                player.body.acceleration.y = 0;
                player.body.velocity.y = 0;
                player.body.allowGravity = false;
                var column = blockData[0];
                var columnSize = this.blocks[column].length;
                if (this.blocks[column][columnSize - 1]['falling'])
                    columnSize--;
                var angle = player.body.touching.left ? 90 : -90;
                var tweenA = this.game.add.tween(player).to({ angle: angle }, (1 / SPEED_PLAYER) * 22500, Phaser.Easing.Linear.None);
                var tweenB = this.game.add.tween(player).to({ y: this.game.world.height - columnSize * block.height - player.height }, (1 / SPEED_PLAYER) * 35000 * (columnSize - blockData[1]), Phaser.Easing.Linear.None);
                var tweenC = this.game.add.tween(player).to({ angle: 0 }, (1 / SPEED_PLAYER) * 22500, Phaser.Easing.Linear.None);
                if (player.body.touching.right) {
                    var tweenD = this.game.add.tween(player).to({ x: player.position.x + player.width }, (1 / SPEED_PLAYER) * 22500, Phaser.Easing.Linear.None);
                }
                else if (player.body.touching.left) {
                    var tweenD = this.game.add.tween(player).to({ x: player.position.x - player.width }, (1 / SPEED_PLAYER) * 22500, Phaser.Easing.Linear.None);
                }
                else {
                    console.error('invalid case for tween');
                    return;
                }
                tweenD.onComplete.add(function () {
                    this.canMove = true;
                    player.body.allowGravity = true;
                }, this);
                tweenA.chain(tweenB.chain(tweenC.chain(tweenD)));
                tweenA.start();
            }
        }
        render() {
            // this.game.debug.body(this.player);
            // for (var i = 0; i < this.coins.length; i++) {
            // this.game.debug.body(this.coins[i]);
            // }
        }
        spawnCoin() {
            var i = randomInteger(0, this.blocks.length - 1);
            var dimBlock = this.dimBlock();
            var dimCoin = dimBlock / 2;
            var coin = this.game.add.sprite(dimBlock * i - dimCoin / 2 + dimBlock / 2, 0, 'coin');
            this.game.physics.enable(coin, Phaser.Physics.ARCADE);
            coin.body.collideWorldBounds = true;
            coin.width = dimCoin;
            coin.height = dimCoin;
            coin.body.allowGravity = false;
            coin.body.bounce = 0;
            this.coins.push(coin);
            this.lastSpawnCoin = this.game.time.totalElapsedSeconds();
            this.nextCoinTime = DELTA_SPAWN_COIN + randomInteger(0, 6);
            this.coinSound.play();
        }
        dimBlock() {
            return this.game.world.width / 9;
        }
        spawnBlock() {
            var i = randomInteger(0, this.blocks.length - 1);
            var attempt = 0;
            while (this.blocks[i].length >= 3 && attempt < 100) {
                i = randomInteger(0, this.blocks.length - 1);
                attempt++;
            }
            if (attempt >= 100)
                return;
            console.log(i);
            var dim = this.dimBlock();
            var block = this.game.add.sprite(dim * i, 0, 'block');
            this.game.physics.enable(block, Phaser.Physics.ARCADE);
            block.body.immovable = true;
            block.width = dim;
            block.height = dim;
            block.body.bounce = 0;
            block.body.allowGravity = false;
            this.blocks[i].push(block);
            block['falling'] = true;
            var tween = this.game.add.tween(block).to({ y: this.game.world.height - block.height * this.blocks[i].length }, this.blockDelay * (6 - this.blocks[i].length), Phaser.Easing.Linear.None, true);
            tween.onComplete.add(function () {
                block['falling'] = false;
                this.checkLastRow();
            }, this);
        }
        checkLastRow() {
            this.blockDelay *= 0.975;
            var lastRowFilled = true;
            for (var i = 0; i < this.blocks.length; i++) {
                if (this.blocks[i].length === 0) {
                    lastRowFilled = false;
                    break;
                }
            }
            if (lastRowFilled) {
                for (var i = 0; i < this.blocks.length; i++) {
                    this.blocks[i][0].kill();
                    this.blocks[i].shift();
                    for (var j = 0; j < this.blocks[i].length; j++) {
                        var block = this.blocks[i][j];
                        var tween = this.game.add.tween(block).to({ y: this.game.world.height - block.height * (j + 1) }, 200, Phaser.Easing.Linear.None, true);
                        tween.onComplete.add(function () {
                            this.toggleSpawnBlock(true);
                        }, this);
                        this.toggleSpawnBlock(true);
                    }
                }
            }
            else {
                this.toggleSpawnBlock(true);
            }
        }
        toggleSpawnBlock(value) {
            if (value)
                this.lastSpawnBlock = this.game.time.totalElapsedSeconds();
            this.canSpawnBlock = value;
        }
    }
    PiouPiou_1.PiouPiou = PiouPiou;
})(PiouPiou || (PiouPiou = {}));
window.onload = () => {
    var game = new PiouPiou.PiouPiou();
};
