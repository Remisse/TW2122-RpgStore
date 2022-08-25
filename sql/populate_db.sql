INSERT INTO `user` (`email`, `password`, `name`) VALUES
('ginopino@mail.com', 'pass2019', 'Gino Pino'),
('cippalippa@mail.com', 'pass2019', 'Cippa Lippa');

INSERT INTO `client` (`user`, `billingaddress`) VALUES
(1, 'Viale Vialone 88, 47521, Cesena (FC)');

INSERT INTO `admin` VALUES
(2);

INSERT INTO `brand` (`brandname`, `brandshortname`, `brandpopularity`, `brandcoverimg`) VALUES
("D&D 5a edizione", "D&D 5e", 1, "dnd-cover.png"),
("Pathfinder 2a edizione", "Pathfinder 2e", 2, "pf2-cover.png"),
("Cyberpunk RED", "Cyberpunk RED", 3, "cpred-cover.png");

INSERT INTO `item` (`itemname`, `itemdescription`, `iteminsertiondate`, `itemimg`, `itemprice`, `itemdiscount`, `itemstock`, `itembrand`, `itemcreator`, `itempublisher`) VALUES
("Manuale del Giocatore (Player's Handbook)", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220830, "dnd5-players-handbook.jpg", 4999, 0.0, 10, 1, "Wizards of the Coast", "Asmodee"),
("Manuale di Gioco", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220830, "pf2-player-guide.jpg", 5999, 0.15, 10, 2, "Paizo", "Giochi Uniti"),
("Manuale Base", "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sollicitudin tellus non volutpat efficitur. Maecenas ultrices sapien vitae libero congue, at faucibus urna convallis. Fusce ultrices congue libero, id lacinia eros tristique eget.", 20220830, "cpred-base-manual.jpg", 5999, 0.0, 5, 3, "R. Talsorian Games", "NEED GAMES");

INSERT INTO `category` (`categoryname`, `categorysuper`) VALUES
("Giochi di ruolo", NULL),
("Manuali", 1),
("Avventure", 1),
("Giochi da tavolo", NULL),
("Accessori", NULL);

INSERT INTO `item_has_category` (`item`, `category`) VALUES
(1, 2),
(2, 2),
(3, 2);
