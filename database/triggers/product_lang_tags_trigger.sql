-- Trigger para manejar automáticamente los tags cuando se actualiza soft_product_lang
-- Este trigger se ejecutará AFTER UPDATE en la tabla soft_product_lang

DELIMITER $$

CREATE TRIGGER tr_product_lang_update_tags
AFTER UPDATE ON soft_product_lang
FOR EACH ROW
BEGIN
    -- Aquí iría la lógica para actualizar los tags basándose en el contenido
    -- Por ahora, el trigger está preparado para implementación futura
    
    -- Ejemplo de lo que podría hacer el trigger:
    -- IF NEW.meta_keywords != OLD.meta_keywords THEN
    --     -- Procesar los tags desde meta_keywords
    -- END IF;
    
    -- El trigger podría:
    -- 1. Extraer tags de campos como meta_keywords, meta_title, etc.
    -- 2. Actualizar la tabla soft_tag
    -- 3. Mantener las relaciones en soft_product_tag
    -- 4. Asegurar que los tags sean específicos para el idioma correcto
    
END$$

DELIMITER ;

-- Para eliminar el trigger:
-- DROP TRIGGER IF EXISTS tr_product_lang_update_tags;
