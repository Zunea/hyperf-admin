export function array2tree (list, tree, parentId, column = 'parent_id') {
    list.forEach((item) => {
        // 判断是否为父级
        if (item[column] === parentId) {
            const child = { ...item, children: [] }
            // 迭代 list， 找到当前相符合的所有子集
            array2tree(list, child.children, item.id)
            // 删掉不存在 children 值的属性
            if (child.children.length <= 0) {
                delete child.children
            }
            // 加入到树中
            tree.push(child)
        }
    })
}
