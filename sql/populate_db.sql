-- Password for both users: 'pass2019'
INSERT INTO `user` (`email`, `password`, `name`, `billingaddress`) VALUES
('ginopino@mail.com', '$2y$10$S5byL4yThqZ2OKla3GGp0.K9ILxAzYNdm6KEx/y.GGTCDI7nd7NCa', 'Gino Pino', 'Viale Vialone 88, 47521, Cesena (FC)'),
('cippalippa@mail.com', '$2y$10$S5byL4yThqZ2OKla3GGp0.K9ILxAzYNdm6KEx/y.GGTCDI7nd7NCa', 'Cippa Lippa', 'Ciaooooooo');

INSERT INTO `admin` VALUES
(2);

INSERT INTO `brand` (`brandname`, `brandshortname`, `brandpopularity`, `brandcoverimg`) VALUES
("D&D 5a edizione", "D&D 5e", 1, "dnd-cover.png"),
("Pathfinder 2a edizione", "Pathfinder 2e", 2, "pf2-cover.png"),
("Cyberpunk RED", "Cyberpunk RED", 3, "cpred-cover.png");

INSERT INTO `category` (`categoryname`, `categorysuper`) VALUES
("Giochi di ruolo", NULL),
("Manuali", 1),
("Avventure", 1),
("Giochi da tavolo", NULL),
("Accessori", NULL);

INSERT INTO `item` (`itemname`, `itemdescription`, `iteminsertiondate`, `itemimg`, `itemprice`, `itemdiscount`, `itemstock`, `itemcategory`, `itembrand`, `itemcreator`, `itempublisher`) VALUES
("Manuale del Giocatore (Player's Handbook)", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220825, "dnd5-players-handbook.jpg", 4999, 0.0, 10, 2, 1, "Wizards of the Coast", "Asmodee"),
("Manuale di Gioco", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220825, "pf2-player-guide.jpg", 5999, 0.15, 10, 2, 2, "Paizo", "Giochi Uniti"),
("Manuale Base", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220825, "cpred-base-manual.jpg", 5999, 0.0, 5, 2, 3, "R. Talsorian Games", "NEED GAMES"),
("Tomb of Annihilation (lingua inglese)", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220830, "dnd5-toa.jpg", 3990, 0.0, 1, 3, 1, "Wizards RPG Team", "Wizards of the Coast");

INSERT INTO `orderstatus` (`statusdescription`) VALUES
("In lavorazione"),
("Spedito"),
("Annullato")
