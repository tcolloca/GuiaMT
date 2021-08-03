package utils {
	
	import puzzles.easter2015.shake.*;

	public class Easter2015Constants {

		public static const magazineItems:Array = new Array("magazine_piece_1");
		
		public static const hfestItems:Array = new Array("ocarina_cantante", "gump", 
			"lata_de_atun_en_aceite_de_oliva", "lapicero_solitario_sin_pegatina_de_perro", 
			"guu_de_bolsillo", "moneda_de_oro", "elixir_de_geminis", "cabeza_de_granito", 
			"altavoz_bajaloya");

		public static const snakeItems:Array = new Array("limon", "bolanio", "biliosa", "higo_jam");

		public static const inventoryItems:Array = new Array(["magazine", magazineItems], ["hammerfest", hfestItems], 
			["snake", snakeItems]);

		public static const fixedHfestItems:Array = new Array([5, "seta_chuli_y_roja"], [8, "loro_decapitado_en_salsa"], 
			[16, "cacahuete_aceitoso_de_malva"], [23, "johnny_6"], [25, "espiritu_de_los_arboles"], 
			[27, "anillo_antok"], [31, "pavo_trufado_sin_cabello_de_angel"], [38, "lomo_embuchado_de_jabugo"], 
			[43, "juicio_penultimo"], [46, "najinata"]);

		public static const movableHfestItems:Array = new Array([1, "sorpresa_fantasmagorica"], [2, "perla_flameante"], 
			[4, "senior_rabano"], [6, "lombriz_al_natural"], [7, "casco_samurai"], [9, "modo_mario"], 
			[10, "brioche_de_vapor"], [12, "pimiento_amarillo"], [18, "queso_holandes_blando"], 
			[19, "caracol_de_chocolate_de_pasta_de_queso"], [20, "esponjita_de_gel_de_vodka"], [22, "liquido_raro"], 
			[23, "johnny_6"], [24, "chupakups_de_clorofila"], [26, "copo_de_nieve_simple"], [28, "pez_emperador"], 
			[29, "pedrusco_de_los_montes"], [32, "reflejo_de_luna"], [33, "maqueta_de_arena"],  
			[34, "gran_azucarado"], [35, "petalo_misterioso"], [36, "caramelo_rosa_praline"], [37, "fibroso_rojito"], 
			[40, "jade_de_12kg"], [41, "hoja_de_arbol"], [42, "factura_gratis"], [44, "flotador_de_pato"], 
			[45, "perla_de_nacar_de_los_murlocs"], [47, "gota_viscosa"], [48, "aperitivo_de_blandiblu"]);

		public static const externalHfestItems:Array = new Array([3, "ocarina_cantante"], [11, "gump"], 
			[13, "lata_de_atun_en_aceite_de_oliva"], [14, "lapicero_solitario_sin_pegatina_de_perro"], 
			[15, "guu_de_bolsillo"], [17, "moneda_de_oro"], [21, "elixir_de_geminis"], [30, "cabeza_de_granito"], 
			[39, "altavoz_bajaloya"]);

		public static const fixedSnakeFruitPacks:Array = new Array(
			new FruitPack(new Fruit("tarudon", 2240, 10, 3, 50, new Array("dulce", "amarillo")), 1),
			new FruitPack(new Fruit("piwi_rosa", 735, 30, 10, 10, new Array("rosa", "amarillo")), 2),
			new FruitPack(new Fruit("albusides", 4830, 50, 20, 10, new Array("alien", "dulce", "amarillo")), 3),
			new FruitPack(new Fruit("musillo", 725, 30, 15, 5, new Array("hoja", "dulce", "roja")), 4),
			new FruitPack(new Fruit("efemerina", 585, 15, 10, 3, new Array("hoja", "dulce", "mini")), 10),
			new FruitPack(new Fruit("naranja", 80, 15, 10, 10, new Array("dulce", "citrico", "naranja")), 1),
			new FruitPack(new Fruit("chankasar", 3630, 20, 0, 10, new Array("dulce", "alien")), 1),
			new FruitPack(new Fruit("marveluna", 4990, 0, 15, 10, new Array("alien", "hoja", "azul")), 1),
			new FruitPack(new Fruit("nuez_de_ziduna", 250, 10, 40, 15, new Array("hoja", "dulce", "nuez", "verde")), 1)
		);

		public static const externalSnakeFruits:Array = new Array(
			new Fruit("limon", 150, 15, 10, 10, new Array("citrico", "amarillo")),
			new Fruit("bolanio", 40, 10, 10, 10, new Array("hoja", "dulce")),
			new Fruit("biliosa", -2625, 0, 0, 10, new Array("alien", "dulce", "roja"))
		);

		public static const diofanticSnakeFruits:Array = new Array(
			new Fruit("frumilla_jam", 1190, 20, 10, 10, new Array("hoja", "dulce", "mini", "liana", "roja")),
			new Fruit("higo_jam", 1220, 10, 5, 10, new Array("hoja", "dulce", "mini", "roja")),
			new Fruit("libernucla_jam", 740, 10, 10, 10, new Array("alien", "dulce", "mini", "roja"))
		);

		public static const bagFruitNames:Array = new Array("tarudon", "piwi_rosa", "albusides",
		 "musillo", "efemerina");

		public static const colorsFruitNames:Array = new Array("limon", "naranja", "biliosa",
			"chankasar", "bolanio", "marveluna", "nuez_de_ziduna");

		public static const diofanticFruitNames:Array = new Array("frumilla_jam", "higo_jam", "libernucla_jam");
	}
}