namespace BinaryLand {

function randomInt(min, max) {
  return Math.floor(Math.random() * (max - min + 1)) + min;
}

function checkOverlap(spriteA, spriteB) {
  if (!spriteA.physicsEnabled || !spriteB.physicsEnabled) {
    return false;
  }
  var boundsA = spriteA.getBounds();
  var boundsB = spriteB.getBounds();
  return Phaser.Rectangle.intersects(boundsA, boundsB);
}

enum Direction {
  Up = 1,
  Down,
  Left,
  Right,
}
export class binaryLand {

  game: Phaser.Game;
  greenPenguin: Phaser.Sprite;
  pinkPenguin: Phaser.Sprite;
  cursor: Phaser.CursorKeys
  internalWalls: Phaser.Sprite[]
  limitWalls: Phaser.Sprite[]
  goal: Phaser.Sprite
  win: Boolean
  spiders: Phaser.Sprite[];
  changeDirectionCounter: number
  timer: Phaser.Text
  lose: Boolean
  shots: Phaser.Sprite[]
  shotsTimeCounter: number
  lastMovement: Direction

  constructor() {
    this.game = new Phaser.Game(17 * 40, 12 * 40, Phaser.AUTO, 'content', {
      preload: this.preload,
      create: this.create,
      update: this.update,
      render: this.render,
      createSpider: this.createSpider
    });
  }

  preload() {
    this.game.load.image('wall', 'assets/sprites/block.png');
    this.game.load.image('goal', 'assets/sprites/goal.png');
    this.game.load.image('heart', 'assets/sprites/heart.png');
    this.game.load.image('shotTop', 'assets/sprites/attack_top.png');
    this.game.load.image('shotDown', 'assets/sprites/attack_bottom.png');
    this.game.load.image('shotLeft', 'assets/sprites/attack_left.png');
    this.game.load.image('shotRight', 'assets/sprites/attack_right.png');
    this.game.load.spritesheet('greenPenguin', 'assets/sprites/green_penguin.png', 18, 18, 8, 0, 0);
    this.game.load.spritesheet('pinkPenguin', 'assets/sprites/pink_penguin.png', 18, 18, 8, 0, 0);
    this.game.load.spritesheet('spider', 'assets/sprites/spider.png', 17, 18, 2, 0, 0);
    this.game.load.atlasJSONHash('player2', '../../assets/player2.png', '../../assets/player.json');
    this.game.load.atlasJSONHash('player1', '../../assets/player.png', '../../assets/player.json');
    this.game.load.atlasJSONHash('boo', 'assets/sprites/boo.png', 'assets/sprites/boo.json');
  }

  create() {
    this.internalWalls = [];
    this.limitWalls = [];
    this.spiders = [];
    this.win = false;
    this.changeDirectionCounter = 0
    this.lose = false;
    this.shots = [];
    this.shotsTimeCounter = 0;

    // Creating limit walls (left, right, top, bottom)
    for (var i = 0; i < this.game.height / 40; i++) {
      for (var j = 0; j < this.game.width / 40; j++) {
        if (i == 0 || j == 0 || i == this.game.height / 40 - 1 || j == this.game.width / 40 - 1) {
          var wall: Phaser.Sprite = this.game.add.sprite(j * 40, i * 40, 'wall');
          wall.width = 40;
          wall.height = 40;
          this.limitWalls.push(wall);
        }
      }
    }
    // this.limitWalls.push(this.game.add.tileSprite(this.game.width - 40, 0, 40, 12 * 40, 'wall', null));
    // this.limitWalls.push(this.game.add.tileSprite(0, 0, 17 * 40, 40 , 'wall', null));
    // this.limitWalls.push(this.game.add.tileSprite(0, this.game.height - 40, 17 * 40, 40 , 'wall', null));
    
    // Creating penguins
    this.pinkPenguin = this.game.add.sprite(this.game.world.width / 2 - 50, this.game.world.height - 40 * 2, 'player2');
    this.pinkPenguin.width = 30;
    this.pinkPenguin.height = 30;
    this.pinkPenguin.animations.add('top', [4, 3, 2, 1].map(n => 'up_' + n + '.png'), 10, true);
    this.pinkPenguin.animations.add('bottom', [4, 3, 2, 1].map(n => 'down_' + n + '.png'), 10, true);
    this.pinkPenguin.animations.add('left', [4, 3, 2, 1].map(n => 'left_' + n + '.png'), 10, true);
    this.pinkPenguin.animations.add('right', [4, 3, 2, 1].map(n => 'right_' + n + '.png'), 10, true);
    this.pinkPenguin.animations.play('left');
    this.game.physics.enable(this.pinkPenguin, Phaser.Physics.ARCADE);
    this.pinkPenguin.physicsEnabled = true;
    this.pinkPenguin.body.drag.set(0.2);
    this.pinkPenguin.body.maxVelocity.setTo(100, 100);

    this.greenPenguin = this.game.add.sprite(this.game.world.width / 2 + 20, this.game.world.height - 40 * 2, 'player1');
    this.greenPenguin.width = 30;
    this.greenPenguin.height = 30;
    this.greenPenguin.animations.add('top', [4, 3, 2, 1].map(n => 'up_' + n + '.png'), 10, true);
    this.greenPenguin.animations.add('bottom', [4, 3, 2, 1].map(n => 'down_' + n + '.png'), 10, true);
    this.greenPenguin.animations.add('left', [4, 3, 2, 1].map(n => 'left_' + n + '.png'), 10, true);
    this.greenPenguin.animations.add('right', [4, 3, 2, 1].map(n => 'right_' + n + '.png'), 10, true);
    this.greenPenguin.animations.play('right');
    this.game.physics.enable(this.greenPenguin, Phaser.Physics.ARCADE);
    this.greenPenguin.body.drag.set(0.2);
    this.greenPenguin.physicsEnabled = true;
    this.greenPenguin.body.maxVelocity.setTo(100, 100);

    // Craeting internal walls

    var positions: number[][] = [[2, 2], [3, 2], [4, 2], [6, 2], [7, 2], [8, 2], [10, 2], [11, 2], [12, 2], [14, 2], [15, 2],
    [1, 3], [2, 3], [4, 3], [6, 3], [8, 3], [9, 3], [10, 3], [12, 3], [14, 3], 
    [8, 4],
    [2, 5], [3, 5], [4, 5], [5, 5], [6, 5], [8, 5], [10, 5], [11, 5], [12, 5], [13, 5], [14, 5], 
    [8, 6], 
    [1, 7], [2, 7], [4, 7], [6, 7], [8, 7], [10, 7], [12, 7], [14, 7], [15, 7],
    [8, 8], 
    [2, 9], [3, 9], [4, 9], [5, 9], [7, 9], [8, 9], [9, 9], [10, 9], [11, 9], [13, 9], [14, 9], 
    [8, 10]];
    for (var i = 0; i < this.game.height / 40; i++) {
      for (var j = 0; j < this.game.width / 40; j++) {
        for (var pos of positions) {
          if (pos[0] == j && pos[1] == i) {
            var wall: Phaser.Sprite = this.game.add.sprite(j * 40, i * 40, 'wall');
            wall.width = 40;
            wall.height = 40;
            this.limitWalls.push(wall);
          }
        }
      }
    }
    // this.internalWalls.push(this.game.add.tileSprite(this.game.world.width / 2 - 20, 40 * 2, 40, 9 * 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 2,  40 * 2, 40 * 3, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 6,  40 * 2, 40 * 3, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 10, 40 * 2, 40 * 3, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 14, 40 * 2, 40 * 2, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 1,  40 * 3, 40 * 2, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 4,  40 * 3, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 6,  40 * 3, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 9,  40 * 3, 40 * 2, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 12, 40 * 3, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 14, 40 * 3, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 2,  40 * 5, 40 * 5, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 10, 40 * 5, 40 * 5, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 1,  40 * 7, 40 * 2, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 4,  40 * 7, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 6,  40 * 7, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 10, 40 * 7, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 12, 40 * 7, 40 * 1, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 14, 40 * 7, 40 * 2, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 2,  40 * 9, 40 * 6, 40, 'wall', null));
    // this.internalWalls.push(this.game.add.tileSprite(40 * 9,  40 * 9, 40 * 6, 40, 'wall', null));

    // Create goal
    this.goal = this.game.add.sprite(40 * 8, 40 * 1, 'goal');
    this.goal.width = 40;
    this.goal.height = 40;

    // Create cursor
    this.cursor = this.game.input.keyboard.createCursorKeys();

    // Set walls rigid body
    for (let wall of this.internalWalls) {
      this.game.physics.enable(wall, Phaser.Physics.ARCADE);
      wall.body.immovable = true;
    }

    for (let wall of this.limitWalls) {
      this.game.physics.enable(wall, Phaser.Physics.ARCADE);
      wall.body.immovable = true;
    }

    // Create spiders
    this.createSpider(40 * 1 , 40 * 1);
    this.createSpider(40 * 10, 40 * 1);
    this.createSpider(40 * 5 , 40 * 4);
    this.createSpider(40 * 13, 40 * 8);
    this.createSpider(40 * 1 , 40 * 10);

    // Create timer
    var time = Math.floor(window.performance.now() / 1000);
    var style = { font: "24px Arial", fill: '#000', align: "center" };
    this.timer = this.game.add.text(5, 5, "" + time, style);

    // Create fire button
    var shot = this.game.input.keyboard.addKey(Phaser.Keyboard.SPACEBAR);
    shot.onDown.add(() => {
      switch (this.lastMovement) {
        case Direction.Down:
          var shot1X = this.greenPenguin.position.x + 8;
          var shot1Y = this.greenPenguin.position.y + 32;
          var shot2X = this.pinkPenguin.position.x + 8;
          var shot2Y = this.pinkPenguin.position.y + 32;
          var shot1 = this.game.add.sprite(shot1X, shot1Y, 'shotDown');
          var shot2 = this.game.add.sprite(shot2X, shot2Y, 'shotDown');
          shot1.height = 20;
          shot2.height = 20;
          shot1.physicsEnabled = true;
          shot2.physicsEnabled = true;
          this.shots.push(shot1);
          this.shots.push(shot2);
          break;

        case Direction.Up:
          var shot1X = this.greenPenguin.position.x + 8;
          var shot1Y = this.greenPenguin.position.y - 18;
          var shot2X = this.pinkPenguin.position.x + 8;
          var shot2Y = this.pinkPenguin.position.y - 18;
          var shot1 = this.game.add.sprite(shot1X, shot1Y, 'shotTop');
          var shot2 = this.game.add.sprite(shot2X, shot2Y, 'shotTop');
          shot1.height = 20;
          shot2.height = 20;
          shot1.physicsEnabled = true;
          shot2.physicsEnabled = true;
          this.shots.push(shot1);
          this.shots.push(shot2);
          break;

        case Direction.Left:
          var shot1X = this.greenPenguin.position.x - 18;
          var shot1Y = this.greenPenguin.position.y + 10;
          var shot2X = this.pinkPenguin.position.x + 32;
          var shot2Y = this.pinkPenguin.position.y + 10;
          var shot1 = this.game.add.sprite(shot1X, shot1Y, 'shotLeft');
          var shot2 = this.game.add.sprite(shot2X, shot2Y, 'shotRight');
          shot1.width = 20;
          shot2.width = 20;
          shot1.physicsEnabled = true;
          shot2.physicsEnabled = true;
          this.shots.push(shot1);
          this.shots.push(shot2);
          break;

        case Direction.Right:
          var shot1X = this.greenPenguin.position.x + 32;
          var shot1Y = this.greenPenguin.position.y + 10;
          var shot2X = this.pinkPenguin.position.x - 18;
          var shot2Y = this.pinkPenguin.position.y + 10;
          var shot1 = this.game.add.sprite(shot1X, shot1Y, 'shotRight');
          var shot2 = this.game.add.sprite(shot2X, shot2Y, 'shotLeft');
          shot1.width = 20;
          shot2.width = 20;
          shot1.physicsEnabled = true;
          shot2.physicsEnabled = true;
          this.shots.push(shot1);
          this.shots.push(shot2);
          break;
      }
    }, this);
  }

  update() {
    // Check collisions
    var greeHitLimitWall = this.game.physics.arcade.collide(this.greenPenguin, this.limitWalls);
    var greenHitInternalWall = this.game.physics.arcade.collide(this.greenPenguin, this.internalWalls);
    var pinkHitLimitWall = this.game.physics.arcade.collide(this.pinkPenguin, this.limitWalls);
    var pinkHitInternalWall = this.game.physics.arcade.collide(this.pinkPenguin, this.internalWalls);
    if (this.spiders.length > 0) {
      var spiderHitLimitWall = this.game.physics.arcade.collide(this.spiders, this.limitWalls);
      var spiderHitInternalWall = this.game.physics.arcade.collide(this.spiders, this.internalWalls);
    }

    var yVel = 0;
    var greenXVel = 0;
    if (!this.win && !this.lose) {
      if (this.cursor.left.isDown) {
        greenXVel = -150;
        this.greenPenguin.animations.play('left');
        this.pinkPenguin.animations.play('right');
        this.lastMovement = Direction.Left;
      } else if (this.cursor.right.isDown) {
        greenXVel = 150;
        this.greenPenguin.animations.play('right');
        this.pinkPenguin.animations.play('left');
        this.lastMovement = Direction.Right;
      } else if (this.cursor.up.isDown) {
        yVel = -150;
        this.greenPenguin.animations.play('top');
        this.pinkPenguin.animations.play('top');
        this.lastMovement = Direction.Up;
      } else if (this.cursor.down.isDown) {
        yVel = 150;
        this.greenPenguin.animations.play('bottom');
        this.pinkPenguin.animations.play('bottom');
        this.lastMovement = Direction.Down;
      } else {
        // this.pinkPenguin.animations.stop();
        // this.greenPenguin.animations.stop();
      }
    }
    this.pinkPenguin.body.velocity.x = -1 * greenXVel;
    this.pinkPenguin.body.velocity.y = yVel;
    this.greenPenguin.body.velocity.x = greenXVel;
    this.greenPenguin.body.velocity.y = yVel;

    // Check win
    var pinkPositionX = 40 * 7;
    var pinkPositionY = 40 * 1;
    var greenPositionX = 40 * 9;
    var greenPositionY = 40 * 1;
    if (
      this.pinkPenguin.position.x <= pinkPositionX + 40 &&
      this.pinkPenguin.position.x >= pinkPositionX &&
      this.pinkPenguin.position.y <= pinkPositionY + 40 &&
      this.pinkPenguin.position.y >= pinkPositionY &&
      this.greenPenguin.position.x <= greenPositionX + 40 &&
      this.greenPenguin.position.x >= greenPositionX &&
      this.greenPenguin.position.y <= greenPositionY + 40 &&
      this.greenPenguin.position.y >= greenPositionY
    ) {
      this.win = true;
      this.pinkPenguin.position.x = pinkPositionX + 20;
      this.pinkPenguin.position.y = pinkPositionY + 5;
      this.greenPenguin.position.x = greenPositionX - 20;
      this.greenPenguin.position.y = greenPositionY + 5;
      this.pinkPenguin.animations.play('right');
      this.greenPenguin.animations.play('left');
      this.goal.kill();
      var heart = this.game.add.sprite(40 * 8 + 7, 40 * 1, 'heart');
      heart.width = 15;
      heart.height = 15;
      for (let spider of this.spiders) {
        spider.kill();
      }
    }

    // Moveing spiders randomly
    if (this.changeDirectionCounter % 100 === 0) {
      for (let spider of this.spiders) {
        var rand = randomInt(0, 100);
        if (rand < 50) {
          var left = rand < 25;
          if (left) {
            spider.animations.play('left'); 
          } else {
            spider.animations.play('right');
          }
          spider.body.velocity.x = 50 * (left ? -1 : 1);
        } else {
          var up = rand > 75;
          spider.body.velocity.y = 50 * (up ? -1 : 1);
        }
      }
      this.changeDirectionCounter = 0;
    }
    this.changeDirectionCounter++;

    // Check losing states
    for (let spider of this.spiders) {
      if (checkOverlap(this.pinkPenguin, spider)) {
        this.lose = true;
        this.pinkPenguin.kill();
        var style = { font: "30px Arial", fill: "#FFF", align: "center" };
        var gameOver = this.game.add.text(this.game.width / 2 - 60, 47, 'Game Over', style);
        this.game.world.sendToBack(gameOver);
        this.goal.kill();
      }
  
      if (checkOverlap(this.greenPenguin, spider)) {
        this.lose = true;
        this.greenPenguin.kill();
        var style = { font: "30px Arial", fill: "#FFF", align: "center" };
        var gameOver = this.game.add.text(this.game.width / 2 - 60, 47, 'Game Over', style);
        this.game.world.sendToBack(gameOver);
        this.goal.kill();
      }
    }

    // Update time
    if (!this.win && !this.lose) {
      var time = Math.floor(window.performance.now() / 1000);
      this.timer.text = time + 's';
    }

    // Remove shots
    if (this.shotsTimeCounter % 10 === 0) {
      for (let shot of this.shots) {
        shot.kill();
      }
      this.shotsTimeCounter = 0;
    }
    this.shotsTimeCounter++;

    // Kill spiders
    var deletedSpiders: Phaser.Sprite[] = []
    for (let shot of this.shots) {
      for (let spider of this.spiders) {
        if (checkOverlap(shot, spider)) {
          spider.kill();
          spider.physicsEnabled = false;
          deletedSpiders.push(spider);
        }
      }
    }
    this.spiders = this.spiders.filter(spider => {
      console.log(deletedSpiders.indexOf(spider));
      return deletedSpiders.indexOf(spider) < 0
    });
   }

  render() {
    // this.game.debug.body(this.pinkPenguin);
    // this.game.debug.body(this.greenPenguin);
    // for (let wall of this.internalWalls) {
    //   this.game.debug.spriteBounds(wall);
    // }
    
    // for (let wall of this.limitWalls) {
    //   this.game.debug.spriteBounds(wall);
    // }
  }

  createSpider(x: number, y: number) {
    var spider = this.game.add.sprite(x, y, 'boo');
    this.game.physics.enable(spider, Phaser.Physics.ARCADE);
    spider.width = 30;
    spider.height = 30;
    spider.body.drag.set(0.2);
    spider.body.maxVelocity.setTo(100, 100);
    var left = [];
    var right = [];
    for (var i = 0; i < 5; i++) {
      left.push(i);
      right.push(63 + i);
    }
    spider.animations.add('left', left.reverse(), 10, true);
    spider.animations.add('right', right.reverse(), 10, true);
    spider.animations.play('right');
    spider.physicsEnabled = true;
    this.spiders.push(spider);
  }
}
}

window.onload = () => {
  var game = new BinaryLand.binaryLand();
}