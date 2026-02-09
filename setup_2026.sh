# ========== setup aco/aros ==================
 app/Console/cake AclExtras.AclExtras aco_sync

# mysql -u root -p ctr -e "
# SET @maxr := (SELECT COALESCE(MAX(rght),0) FROM aros);
# INSERT INTO aros (parent_id, model, foreign_key, alias, lft, rght)
# SELECT NULL, 'Group', 9, NULL, @maxr+1, @maxr+2
# WHERE NOT EXISTS (
#   SELECT 1 FROM aros WHERE model='Group' AND foreign_key=9
# );
# SELECT id,parent_id,model,foreign_key,lft,rght FROM aros WHERE model='Group' AND foreign_key=9;
# "

 #============================================
 #=============================================
 #===========================================
 #=======================================