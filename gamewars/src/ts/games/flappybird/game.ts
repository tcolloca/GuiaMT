
namespace FlappyBird {

	export class FlappyBird {

		private gameHeight: number = Math.min(window.innerHeight, 500);
		private gameWidth: number = window.innerWidth / window.innerHeight * this.gameHeight;

		private gravity: number = 2000;
		private jumpSpeed: number = 450;
		private initialHorizontalSpeed: number = 250;
		private maxHorizontalSpeed: number = 400;
		private timeTillMaxSpeed: number = 90000;
		
		private pipeHoleHeight: number = 150;
		private minPipeHole: number = 100;
		private maxPipeHole: number = this.gameHeight - this.pipeHoleHeight - 100;
		private pipePosition: number = 0;
		private pipeHeight: number = 500;
		
		private startPipeDelay: number = 1500;
		private endPipeDelay: number = 800;

		private jumpAngle: number = 20;
		private jumpAnimationTime: number = 100;
		private playerXAnchor: number = -0.2;
		private playerYAnchor: number = 0.5;

		private jumpSound: Phaser.Sound;
		private deathSound: Phaser.Sound;

		private startState = {
			preload: this.startPreload.bind(this), 
			create: this.startCreate.bind(this), 
			update: this.startUpdate.bind(this) 
		}

		private gameState = { 
			preload: this.gamePreload.bind(this), 
			create: this.gameCreate.bind(this), 
			update: this.gameUpdate.bind(this) 
		};

		private game: Phaser.Game = new Phaser.Game(this.gameWidth, this.gameHeight, Phaser.AUTO);

		private player: Phaser.Sprite;
		private pipes: Phaser.Group;
		private timer: Phaser.TimerEvent;
		private gameTime: Phaser.Time;
		private score: number;
		private labelScore: Phaser.Text;

		private startTime: number = 0;

		private jumpKey: Phaser.Key;
		private jumping: boolean = false;

		constructor() {
			this.game.state.add('start', this.startState);
			this.game.state.add('game', this.gameState);
			this.game.state.start('start');
		}

		private startPreload(): void { 
		    this.game.load.atlasJSONHash('player', '../../assets/player.png', '../../assets/player.json');
		    this.game.load.image('top_pipe', 'assets/top_pipe.png');
		    this.game.load.image('bottom_pipe', 'assets/bottom_pipe.png');
		    this.game.load.image('background', 'assets/background.png');
		    this.game.load.image('button', '../../assets/button.png');
		    this.game.load.audio('jump', 'assets/jump.wav'); 
		    this.game.load.audio('death', 'assets/cuek.swf.mp3'); 
			if (this.game.height < window.innerHeight) {
		    	this.game.scale.scaleMode = Phaser.ScaleManager.EXACT_FIT;
		    	this.game.scale.refresh();
		    	// this.game.width = window.innerWidth;
		    	// this.game.height = window.innerHeight;
		    	// this.gameWidth = window.innerWidth;
		    	// this.gameHeight = window.innerHeight;
		    }
		}

		private startCreate(): void { 
		    var background: Phaser.Sprite = this.game.add.sprite(0, 0, 'background');
			if (this.gameWidth > this.gameHeight) {
		    	background.width = this.gameWidth;
		    	background.scale.y = background.scale.x;
			} else {
				background.height = this.gameHeight;
		    	background.scale.x = background.scale.y;
			}
		    background.position.y = -(background.height - this.gameHeight);
		    this.player = this.game.add.sprite(100, this.gameHeight / 2, 'player');
		    this.player.scale.multiply(1.25, 1.25);
 		    this.player.animations.add('right', [4, 3, 2, 1].map(n => 'right_' + n + '.png'), 10, true);
		    this.player.animations.play('right');

 		    this.jumpKey = this.game.input.keyboard.addKey(Phaser.KeyCode.SPACEBAR);

		    this.score = 0;
			this.labelScore = this.game.add.text(20, 20, "0", 
			    { font: "30px Arial", fill: "#ffffff" });   

			this.jumpSound = this.game.add.audio('jump'); 
			this.deathSound = this.game.add.audio('death'); 
		}

		private startUpdate(): void {
			if (this.game.input.pointer1.isDown || this.jumpKey.isDown) {
				this.game.state.start('game');
			}
		}

		private gamePreload(): void {
		}

		private gameCreate(): void {
			this.startCreate();
		    this.game.physics.startSystem(Phaser.Physics.ARCADE);
		    
		    this.player.anchor.setTo(this.playerXAnchor, this.playerYAnchor); 
		    this.game.physics.arcade.enable(this.player);
		    this.player.body.gravity.y = this.gravity;

		    this.pipes = this.game.add.group();  
			this.timer = this.game.time.events.loop(this.startPipeDelay, this.addRowOfPipes, this); 

			this.gameTime = this.game.time;
			this.startTime = this.gameTime.now;

			this.labelScore.bringToTop();
		}

		private gameUpdate(): void {
			this.horizontalSpeed;
			if (this.player.angle < this.jumpAngle) {
    			this.player.angle += 1; 
			}
		    if (this.player.y < 0 || this.player.y > this.gameHeight) {
		        this.endGame();
		    }
			if (!this.player.alive) {
				return;
			}
			if (this.game.input.pointer1.isDown || this.jumpKey.isDown) {
				this.jump();
			} else {
				this.jumping = false;
			}
			this.updateDelay();

		    this.game.physics.arcade.overlap(this.player, this.pipes, this.hitPipe.bind(this));
		}

		private jump(): void {
			if (!this.jumping) {
				this.jumpSound.play();
			}
			this.jumping = true;
		    this.player.body.velocity.y = -this.jumpSpeed;

		    this.game.add.tween(this.player)
		    	.to({angle: -this.jumpAngle}, this.jumpAnimationTime).start(); 
		}

		private endGame(): void {
			this.player.alive = false;
			this.labelScore.position.x = this.game.world.centerX;
			this.labelScore.position.y = this.game.world.centerY - 100;
			this.labelScore.setStyle({
				font: "60px Arial",
				fontWeight: "bold",
				fill: "#FFFFFF"
			});
			this.labelScore.position.x -= this.labelScore.width / 2;

			this.addButton('Play Again', 50, () => this.game.state.start('start'));
			this.addButton('Back To Map', 150, () => window.location.href = "../..");
			// var button: Phaser.Sprite = this.game.add.sprite(this.game.world.centerX, this.game.world.centerY + 50, 'button');
			// button.position.x -= button.width / 2;
			// button.inputEnabled = true;
			// button.events.onInputDown.add(() => {
			// 	console.log("click");
			// 	window.location.href = "../..";
			// });

			// var backToMapText: Phaser.Text = this.game.add.text(0, 0, "Back to Map", 
			//     { font: "24px Arial", fill: "#ffffff" });  
			// backToMapText.position.x = button.x + (button.width - backToMapText.width) / 2;
			// backToMapText.position.y = button.y + (button.height - backToMapText.height) / 2;
		}

		private addButton(content: string, y: number, callback: Function): void {
			var button: Phaser.Sprite = this.game.add.sprite(this.game.world.centerX, this.game.world.centerY + y, 'button');
			button.position.x -= button.width / 2;
			button.inputEnabled = true;
			button.events.onInputDown.add(callback);

			var backToMapText: Phaser.Text = this.game.add.text(0, 0, content, 
			    { font: "24px Arial", fill: "#ffffff" });  
			backToMapText.position.x = button.x + (button.width - backToMapText.width) / 2;
			backToMapText.position.y = button.y + (button.height - backToMapText.height) / 2;
		}

		private addPipe(x: number, y: number, name: string): Phaser.Sprite {
		    var pipe: Phaser.Sprite = this.game.add.sprite(x, y, name);

		    this.game.physics.arcade.enable(pipe);

		    pipe.body.velocity.x = -this.horizontalSpeed; 

		    pipe.checkWorldBounds = true;
		    pipe.outOfBoundsKill = true;

		    this.pipes.add(pipe);

		    return pipe;
		}

		private addRowOfPipes(): void {
		    var holeStart: number = Math.random() * (this.maxPipeHole - this.minPipeHole) + this.minPipeHole;
		    var holeEnd: number = holeStart + this.pipeHoleHeight;

		    var topPipe: Phaser.Sprite = this.addPipe(this.gameWidth - this.pipePosition, holeStart - this.pipeHeight, 'top_pipe');
		    var bottomPipe: Phaser.Sprite = this.addPipe(this.gameWidth - this.pipePosition, holeEnd, 'bottom_pipe');

		    // for (var i: number = 0; i < this.gameHeight / totalPipeHeight; i++) {
		    //     if (i != hole && i != hole + 1) {
		    //         pipe = this.addOnePipe(this.gameWidth - this.pipePosition, i * totalPipeHeight);
		    //     }
		    // }
			bottomPipe.events.onKilled.add(this.addScore.bind(this));
		}

		private hitPipe(): void {
		    if (this.player.alive == false) {
		        return;
		    }
		    this.deathSound.play();

		    this.player.alive = false;
		    this.game.time.events.remove(this.timer);

		    this.pipes.forEach((pipe: Phaser.Sprite) => {
		        pipe.body.velocity.x = 0;
		    }, this);
		}

		private addScore(): void  {
			if (!this.player.alive) {
				return;
			}
		    this.score += 1;
			this.labelScore.text = String(this.score);
		}

		get horizontalSpeed(): number {
			var time: number = this.gameTime.now - this.startTime;
			if (time > this.timeTillMaxSpeed) {
				time = this.timeTillMaxSpeed;
			}
			return this.lerp(this.initialHorizontalSpeed, this.maxHorizontalSpeed, this.getDifficulty());
		}

		private updateDelay(): void {
			this.timer.delay = this.lerp(this.startPipeDelay, this.endPipeDelay, this.getDifficulty());
		}

		private getDifficulty(): number {
			var time: number = this.gameTime.now - this.startTime;
			if (time > this.timeTillMaxSpeed) {
				time = this.timeTillMaxSpeed;
			}
			return time / this.timeTillMaxSpeed;
		}

		private lerp(start: number, end: number, p: number): number {
    		return (end - start) * p + start;
    	}
	}
}

window.onload = () => {
	var game: FlappyBird.FlappyBird = new FlappyBird.FlappyBird();
}


